<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VirtualAccount;
use App\Models\Transaction;

class WebhookController extends Controller
{
    public function paystack(Request $request)
    {
        \Log::info('========== WEBHOOK REACHED ==========');

        try {

            $payload = $request->getContent();

            $signature = $request->header('x-paystack-signature');

            \Log::info('SIGNATURE RECEIVED');

            if (
                $signature !== hash_hmac(
                    'sha512',
                    $payload,
                    config('services.paystack.secret')
                )
            ) {
                \Log::info('PAYSTACK SECRET: ' . config('services.paystack.secret'));
                \Log::error('INVALID SIGNATURE');

                return response()->json([
                    'message' => 'Invalid signature'
                ], 401);
            }

            \Log::info('SIGNATURE VERIFIED');

            $event = $request->all();

            \Log::info('FULL EVENT');
            \Log::info($event);

            if (($event['event'] ?? null) !== 'charge.success') {

                \Log::info('EVENT IGNORED');

                return response()->json([
                    'message' => 'Ignored'
                ]);
            }

            $data = $event['data'];

            $channel = $data['channel'] ?? 'unknown';

            \Log::info('CHANNEL: ' . $channel);

            switch ($channel) {

                /*
                |--------------------------------------------------------------------------
                | DVA FUNDING
                |--------------------------------------------------------------------------
                */
                case 'dedicated_nuban':

                    \Log::info('PROCESSING DVA');

                    $accountNumber =
                        $data['metadata']['receiver_account_number']
                        ?? $data['authorization']['receiver_bank_account_number']
                        ?? null;

                    \Log::info('ACCOUNT NUMBER: ' . $accountNumber);

                    if (!$accountNumber) {

                        \Log::error('ACCOUNT NUMBER NOT FOUND');

                        return response()->json([
                            'message' => 'No account number'
                        ]);
                    }

                    $virtualAccount = VirtualAccount::where(
                        'account_number',
                        $accountNumber
                    )->first();

                    \Log::info('VIRTUAL ACCOUNT RESULT');
                    \Log::info($virtualAccount);

                    if (!$virtualAccount) {

                        \Log::error(
                            'VIRTUAL ACCOUNT NOT FOUND: ' .
                            $accountNumber
                        );

                        return response()->json([
                            'message' => 'Virtual account not found'
                        ]);
                    }

                    $reference = $data['reference'];

                    \Log::info('REFERENCE: ' . $reference);

                    if (
                        Transaction::where(
                            'reference',
                            $reference
                        )->exists()
                    ) {

                        \Log::info('REFERENCE ALREADY EXISTS');

                        return response()->json([
                            'message' => 'Already processed'
                        ]);
                    }

                    DB::transaction(function () use (
                        $virtualAccount,
                        $data,
                        $reference
                    ) {

                        \Log::info('START DB TRANSACTION');

                        $user = $virtualAccount->user;

                        \Log::info('USER ID: ' . $user->id);

                        $wallet = $user->wallet;

                        \Log::info('WALLET BEFORE: ' . $wallet->balance);

                        $amount = $data['amount'] / 100;

                        \Log::info('AMOUNT: ' . $amount);

                        $wallet->increment(
                            'balance',
                            $amount
                        );

                        $wallet->refresh();

                        \Log::info(
                            'WALLET AFTER: ' .
                            $wallet->balance
                        );

                        Transaction::create([
                            'user_id'   => $user->id,
                            'reference' => $reference,
                            'type'      => 'wallet_funding_dva',
                            'amount'    => $amount,
                            'status'    => 'success',
                            'processed' => true,
                            'details'   => json_encode($data)
                        ]);

                        \Log::info('TRANSACTION SAVED');
                    });

                    \Log::info('DVA COMPLETED');

                    break;

                /*
                |--------------------------------------------------------------------------
                | CARD FUNDING
                |--------------------------------------------------------------------------
                */
                default:

                    \Log::info('PROCESSING CARD PAYMENT');

                    $reference =
                        $data['reference'] ?? null;

                    \Log::info('REFERENCE: ' . $reference);

                    $transaction = Transaction::where(
                        'reference',
                        $reference
                    )->first();

                    \Log::info('TRANSACTION RESULT');
                    \Log::info($transaction);

                    if (!$transaction) {

                        \Log::error(
                            'TRANSACTION NOT FOUND'
                        );

                        return response()->json([
                            'message' => 'Transaction not found'
                        ]);
                    }

                    if ($transaction->processed) {

                        \Log::info('ALREADY PROCESSED');

                        return response()->json([
                            'message' => 'Already processed'
                        ]);
                    }

                    DB::transaction(function () use (
                        $transaction
                    ) {

                        $wallet =
                            $transaction->user->wallet;

                        \Log::info(
                            'WALLET BEFORE: ' .
                            $wallet->balance
                        );

                        $wallet->increment(
                            'balance',
                            $transaction->amount
                        );

                        $wallet->refresh();

                        \Log::info(
                            'WALLET AFTER: ' .
                            $wallet->balance
                        );

                        $transaction->update([
                            'status' => 'success',
                            'processed' => true
                        ]);

                        \Log::info(
                            'CARD TRANSACTION UPDATED'
                        );
                    });

                    break;

                /*
                |--------------------------------------------------------------------------
                | MANUAL FUNDING (FUTURE)
                |--------------------------------------------------------------------------
                */
                case 'manual':

                    \Log::info('MANUAL FUNDING');

                    break;
            }

            \Log::info('========== WEBHOOK SUCCESS ==========');

            return response()->json([
                'message' => 'Success'
            ]);

        } catch (\Exception $e) {

            \Log::error(
                'WEBHOOK ERROR: ' .
                $e->getMessage()
            );

            \Log::error(
                'FILE: ' .
                $e->getFile()
            );

            \Log::error(
                'LINE: ' .
                $e->getLine()
            );

            return response()->json([
                'message' => 'Error'
            ], 500);
        }
    }
}