<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPlan;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\VtpassService;

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


public function buy(Request $request, VtpassService $vtpass)
{
    $request->validate([
        'phone' => 'required|digits:11',
        'variation_code' => 'required',
        'network' => 'required'
    ]);

    $user = auth()->user();

    DB::beginTransaction();

    try {

        $wallet = $user->wallet()->lockForUpdate()->first();

        // 🔹 get fresh plans from service
        $plans = $vtpass->getDataPlans($request->network);

        $plan = collect($plans)->firstWhere('variation_code', $request->variation_code);

        if (!$plan) {
            return back()->with('error', 'Invalid plan');
        }

        $amount = (float) $plan['variation_amount'];

        if ($wallet->balance < $amount) {
            return back()->with('error', 'Insufficient balance');
        }

        // deduct
        $wallet->balance -= $amount;
        $wallet->save();

        // transaction
        $tx = Transaction::create([
            'user_id' => $user->id,
            'type' => 'data',
            'amount' => $amount,
            'status' => 'pending',
            'reference' => uniqid('DATA-')
        ]);

        // 🔹 call service
        $result = $vtpass->buyData(
            $tx->reference,
            $request->phone,
            $request->network,
            $request->variation_code,
            $amount
        );

        $desc = strtolower($result['response_description'] ?? '');

        if (str_contains($desc, 'successful')) {

            $tx->update(['status' => 'success']);

            DB::commit();

            return back()->with('success', 'Data successful');
        }

        // refund
        $wallet->balance += $amount;
        $wallet->save();

        $tx->update(['status' => 'failed']);

        DB::commit();

        return back()->with('error', 'Failed → Refunded');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'System error');
    }
}
}