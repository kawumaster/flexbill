<?php

namespace App\Http\Controllers;

use App\Models\VirtualAccount;
use App\Services\PaystackService;

class VirtualAccountController extends Controller
{
    public function create(PaystackService $paystack)
    {
        $user = auth()->user();

        // Prevent duplicate account
        if (VirtualAccount::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Virtual account already exists');
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 1: CREATE CUSTOMER
        |--------------------------------------------------------------------------
        */
        $customer = $paystack->createCustomer($user);

        if (
            !isset($customer['status']) ||
            !$customer['status']
        ) {
            return back()->with(
                'error',
                $customer['message'] ?? 'Customer creation failed'
            );
        }

        $customer_code = $customer['data']['customer_code'];

        /*
        |--------------------------------------------------------------------------
        | STEP 2: CREATE VIRTUAL ACCOUNT
        |--------------------------------------------------------------------------
        */
        $account = $paystack->createDVA($customer_code);

        // DEBUG PAYSTACK RESPONSE
        if (
            !isset($account['status']) ||
            !$account['status']
        ) {
            return back()->with(
                'error',
                $account['message'] ?? 'DVA generation failed'
            );
        }

        if (!isset($account['data']['account_number'])) {
            return back()->with('error', 'No account number returned');
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 3: SAVE TO DATABASE
        |--------------------------------------------------------------------------
        */
        VirtualAccount::create([
            'user_id' => $user->id,
            'account_number' => $account['data']['account_number'],
            'account_name' => $account['data']['account_name'],
            'bank_name' => $account['data']['bank']['name'],
            'customer_code' => $customer_code,
        ]);

        return back()->with('success', 'Virtual account created successfully');
    }
}