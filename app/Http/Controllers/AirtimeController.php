<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


// use App\Http\Controllers\AirtimeController;


class AirtimeController extends Controller
{


public function index()
{
    return view('airtime.buy'); // create this view
}

public function buyAirtime(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'amount' => 'required|numeric|min:100',
        'network' => 'required'
    ]);

    $user = auth()->user();
    $wallet = $user->wallet;

    if ($wallet->balance < $request->amount) {
        return back()->with('error', 'Insufficient wallet balance');
    }

    DB::beginTransaction();

    try {

        // Deduct
        $wallet->balance -= $request->amount;
        $wallet->save();

        // Transaction record
        $tx = Transaction::create([
            'user_id' => $user->id,
            'type' => 'airtime',
            'amount' => $request->amount,
            'status' => 'pending',
            'reference' => uniqid('VTU-')
        ]);

        // Call VTpass
        $response = Http::withHeaders([
    'api-key' => env('VTPASS_API_KEY'),
    'secret-key' => env('VTPASS_SECRET_KEY'),
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
])->post(env('VTPASS_BASE_URL') . '/api/pay', [
    'request_id' => $tx->reference,
    'serviceID' => $request->network,
    'amount' => $request->amount,
    'phone' => $request->phone,
]);

        $result = $response->json();

        \Log::info('VTU RESPONSE:', ['data' => $result]);

        // SUCCESS
        if (isset($result['code']) && $result['code'] == '000') {

            $tx->update(['status' => 'success']);

            DB::commit();

            return back()->with('success', 'Airtime sent successfully');
        }

        // FAIL → REFUND
        $wallet->balance += $request->amount;
        $wallet->save();

        $tx->update(['status' => 'failed']);

        DB::commit();

        return back()->with('error', 'Airtime failed → Refunded');

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error('VTU ERROR:', ['error' => $e->getMessage()]);

        return back()->with('error', 'System error, try again');
    }
}
}