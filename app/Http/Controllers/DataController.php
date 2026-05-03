<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPlan;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
 public function index()
{
    $response = Http::timeout(60)->withHeaders([
        'api-key' => env('VTPASS_API_KEY'),
        'secret-key' => env('VTPASS_SECRET_KEY'),
        'Content-Type' => 'application/json',
    ])->get('https://sandbox.vtpass.com/api/service-variations', [
        'serviceID' => 'mtn-data'
    ]);

    $data = $response->json();

    logger()->info('VTPASS DATA RESPONSE', $data ?? []);

    $plans = $data['content']['variations'] ?? [];

    return view('data.index', compact('plans'));
}

public function getPlans($network)
{
    $response = Http::timeout(60)->withHeaders([
        'api-key' => env('VTPASS_API_KEY'),
        'secret-key' => env('VTPASS_SECRET_KEY'),
        'Content-Type' => 'application/json',
    ])->get('https://sandbox.vtpass.com/api/service-variations', [
        'serviceID' => $network . '-data'
    ]);

    $data = $response->json();

    return response()->json($data['content']['variations'] ?? []);
}

public function buy(Request $request)
{
    $request->validate([
        'phone' => 'required|digits:11',
        'variation_code' => 'required',
        'network' => 'required|in:mtn,airtel,glo,9mobile'
    ]);

    $user = auth()->user();

    DB::beginTransaction();

    try {

        // 🔒 lock wallet
        $wallet = $user->wallet()->lockForUpdate()->first();

        $serviceID = $request->network . '-data';

        // ✅ fetch correct plans based on network
        $response = Http::timeout(60)->withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->get('https://sandbox.vtpass.com/api/service-variations', [
            'serviceID' => $serviceID
        ]);

        $plans = $response->json()['content']['variations'] ?? [];

        $plan = collect($plans)->firstWhere('variation_code', $request->variation_code);

        if (!$plan) {
            return back()->with('error', 'Invalid data plan');
        }

        $amount = (float) $plan['variation_amount'];

        if ($wallet->balance < $amount) {
            return back()->with('error', 'Insufficient balance');
        }

        // 💸 deduct
        $wallet->balance -= $amount;
        $wallet->save();

        // 🧾 transaction
        $tx = Transaction::create([
            'user_id' => $user->id,
            'type' => 'data',
            'amount' => $amount,
            'status' => 'pending',
            'reference' => uniqid('DATA-')
        ]);

        // 🚀 API CALL
        $response = Http::timeout(60)->withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.vtpass.com/api/pay', [
            'request_id' => $tx->reference,
            'serviceID' => $serviceID, // ✅ FIXED
            'billersCode' => $request->phone,
            'variation_code' => $request->variation_code,
            'amount' => $amount,
            'phone' => $request->phone,
        ]);

        $result = $response->json();

        $desc = strtolower($result['response_description'] ?? '');

        if (str_contains($desc, 'successful')) {

            $tx->update(['status' => 'success']);

            DB::commit();

            return back()->with('success', 'Data purchased successfully');
        }

        // ❌ refund
        $wallet->balance += $amount;
        $wallet->save();

        $tx->update(['status' => 'failed']);

        DB::commit();

        return back()->with('error', 'Transaction failed, refunded');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'System error, try again');
    }
}
}