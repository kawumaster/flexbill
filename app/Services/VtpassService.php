<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class VtpassService
{
    protected $baseUrl;
    protected $headers;

    public function __construct()
    {
        $this->baseUrl = env('VTPASS_BASE_URL', 'https://vtpass.com');

        $this->headers = [
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    // 🔹 Get Data Plans
    public function getDataPlans($network)
    {
        $response = Http::withHeaders($this->headers)
            ->timeout(60)
            ->get($this->baseUrl . '/api/service-variations', [
                'serviceID' => $network . '-data'
            ]);

        return $response->json()['content']['variations'] ?? [];
    }

    // 🔹 Buy Data
    public function buyData($reference, $phone, $network, $variationCode, $amount)
    {
        $response = Http::withHeaders($this->headers)
            ->timeout(60)
            ->post($this->baseUrl . '/api/pay', [
                'request_id' => $reference,
                'serviceID' => $network . '-data',
                'billersCode' => $phone,
                'variation_code' => $variationCode,
                'amount' => $amount,
                'phone' => $phone,
            ]);

        return $response->json();
    }

    // 🔹 Buy Airtime
    public function buyAirtime($reference, $phone, $network, $amount)
    {
        $response = Http::withHeaders($this->headers)
            ->timeout(60)
            ->post($this->baseUrl . '/api/pay', [
                'request_id' => $reference,
                'serviceID' => $network,
                'amount' => $amount,
                'phone' => $phone,
            ]);

        return $response->json();
    }
}