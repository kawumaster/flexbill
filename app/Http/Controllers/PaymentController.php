<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class PaymentController extends Controller
{
    // =========================
    // 💰 FUND WALLET (PAYSTACK)
    // =========================
    public function fundWallet(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $reference = Str::uuid();

        // ✅ Save transaction
        Transaction::create([
            'user_id' => auth()->id(),
            'reference' => $reference,
            'type' => 'wallet_funding_card',
            'amount' => $request->amount,
            'status' => 'pending',
            'processed' => false
        ]);

        // ✅ Initialize Paystack payment
        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $request->amount * 100, // kobo
                'reference' => $reference,
                'callback_url' => url('/payment/callback'),
            ]);

        $data = $response->json();

        // ❗ Handle error
        if (!isset($data['status']) || !$data['status']) {
            return back()->with('error', 'Payment initialization failed');
        }

        // ✅ Redirect to Paystack
        return redirect($data['data']['authorization_url']);
    }

    // =========================
    // 🔔 PAYSTACK WEBHOOK
    // =========================
   public function webhook(Request $request)
{
    \Log::info('WEBHOOK HIT');
    \Log::info($request->all());

    try {

        $event = $request->all();

        if (($event['event'] ?? null) !== 'charge.success') {
            return response()->json(['ignored']);
        }

        $reference = $event['data']['reference'] ?? null;

        \Log::info('REF: ' . $reference);

        if (!$reference) {
            return response()->json(['no ref']);
        }

        $transaction = \App\Models\Transaction::where('reference', $reference)->first();

        if (!$transaction) {
            \Log::error('NOT FOUND: ' . $reference);
            return response()->json(['not found']);
        }

        if ($transaction->processed) {
            return response()->json(['already done']);
        }

        if ($transaction->type === 'wallet_funding') {

            $user = $transaction->user;

            if (!$user) {
                \Log::error('NO USER');
                return response()->json(['no user']);
            }

            $userWallet = $user->wallet; // relationship

$userWallet->balance += $transaction->amount;
$userWallet->save();

            $transaction->update([
                'status' => 'success',
                'processed' => true
            ]);

            \Log::info('WALLET UPDATED');
        }

        return response()->json(['ok']);

    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json(['error'], 500);
    }
}
}



