<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class VtpassService
{
    public function buyData($phone, $plan, $amount)
    {
        return Http::withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
        ])->post(env('VTPASS_BASE_URL') . '/pay', [
            'request_id' => uniqid(),
            'serviceID' => 'mtn-data',
            'billersCode' => $phone,
            'variation_code' => $plan,
            'amount' => $amount,
            'phone' => $phone,
        ])->json();
    }
}

