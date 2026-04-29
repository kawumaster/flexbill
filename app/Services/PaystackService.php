<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    public function initializePayment($email, $amount, $reference)
    {
        return Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $email,
                'amount' => $amount * 100,
                'reference' => $reference,
                'callback_url' => route('payment.callback'),
            ])->json();
    }
}