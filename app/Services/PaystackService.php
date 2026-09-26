<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    protected $baseUrl;
    protected $secret;

    public function __construct()
    {
        $this->baseUrl = "https://api.paystack.co";
        $this->secret = env('PAYSTACK_SECRET_KEY');
    }

    protected function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->secret,
            'Content-Type' => 'application/json',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CARD PAYMENT
    |--------------------------------------------------------------------------
    */
    public function initializePayment($email, $amount, $reference)
    {
        return Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/transaction/initialize', [
                'email' => $email,
                'amount' => $amount * 100,
                'reference' => $reference,
                'callback_url' => route('payment.callback'),
            ])
            ->json();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE CUSTOMER
    |--------------------------------------------------------------------------
    */
 public function createCustomer($user)
{
    $response = Http::withHeaders($this->headers())
        ->post($this->baseUrl . '/customer', [
            'email'      => $user->email,
            'first_name' => $user->name,
            'last_name'  => 'user',
            'phone'      => $user->phone,
        ]);

    return $response->json();
}
    /*
    |--------------------------------------------------------------------------
    | CREATE DEDICATED VIRTUAL ACCOUNT
    |--------------------------------------------------------------------------
    */
    public function createDVA($customer_code)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/dedicated_account', [
                'customer' => $customer_code
            ]);

        return $response->json();
    }
}