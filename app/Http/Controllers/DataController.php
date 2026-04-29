<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPlan;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Support\Facades\Http;

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

 public function buy(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'variation_code' => 'required'
    ]);

    $user = auth()->user();
    $wallet = $user->wallet;

    // GET PLAN AGAIN
    $response = Http::timeout(60)->withHeaders([
        'api-key' => env('VTPASS_API_KEY'),
        'secret-key' => env('VTPASS_SECRET_KEY'),
        'Content-Type' => 'application/json',
    ])->get('https://sandbox.vtpass.com/api/service-variations', [
        'serviceID' => 'mtn-data'
    ]);

    $data = $response->json();

    $plans = $data['content']['variations'] ?? [];

    $plan = collect($plans)->firstWhere('variation_code', $request->variation_code);

    if (!$plan) {
        return back()->with('error', 'Invalid data plan');
    }

    $amount = (float) $plan['variation_amount'];

    if ($wallet->balance < $amount) {
        return back()->with('error', 'Insufficient balance');
    }

    // deduct
    $wallet->balance -= $amount;
    $wallet->save();

    $tx = Transaction::create([
        'user_id' => $user->id,
        'type' => 'data',
        'amount' => $amount,
        'status' => 'pending',
        'reference' => uniqid('DATA-')
    ]);

    try {

        $response = Http::timeout(60)->withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.vtpass.com/api/pay', [
            'request_id' => $tx->reference,
            'serviceID' => 'mtn-data',
            'billersCode' => $request->phone,
            'variation_code' => $request->variation_code,
            'amount' => $amount,
            'phone' => $request->phone,
        ]);

$result = $response->json();

logger()->info('DATA RESPONSE', $result ?? []);

$desc = strtolower($result['response_description'] ?? '');

if (str_contains($desc, 'successful')) {

    $tx->update(['status' => 'success']);

    return back()->with('success', 'Data purchased successfully');
}

    } catch (\Exception $e) {
        logger()->error('DATA ERROR', ['error' => $e->getMessage()]);
    }

    // refund
    $wallet->balance += $amount;
    $wallet->save();

    $tx->update(['status' => 'failed']);

    return back()->with('error', 'Transaction failed, refunded');
}
}