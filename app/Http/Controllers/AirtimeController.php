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

//auto detect function

private function detectNetwork($phone)
{
    $prefix4 = substr($phone, 0, 4);
    $prefix5 = substr($phone, 0, 5);

    $networks = [
        'mtn' => ['0801','0803','0806','0813','0816','0810','0814','0903','0906','0703','0706','0704','07025','07026'],
        'glo' => ['0805','0807','0815','0811','0905','0705'],
        'airtel' => ['0802','0808','0812','0701','0708','0902','0907','0901'],
        '9mobile' => ['0809','0817','0818','0909','0908']
    ];

    foreach ($networks as $network => $prefixes) {
        if (in_array($prefix4, $prefixes) || in_array($prefix5, $prefixes)) {
            return $network;
        }
    }

    return null;
}

public function buyAirtime(Request $request)
{
    $request->validate([
        'phone' => 'required|digits:11',
        'amount' => 'required|numeric|min:50'
    ]);

    $network = $this->detectNetwork($request->phone);

    if (!$network) {
        return back()->with('error', 'Invalid phone number');
    }

    $user = auth()->user();

    DB::beginTransaction();

    try {

        // 🔒 lock wallet
        $wallet = $user->wallet()->lockForUpdate()->first();

        if ($wallet->balance < $request->amount) {
            DB::rollBack(); // ✅ FIX
            return back()->with('error', 'Insufficient balance');
        }

        // 💸 deduct
        $wallet->balance -= $request->amount;
        $wallet->save();

        // 🧾 transaction
        $tx = Transaction::create([
            'user_id' => $user->id,
            'type' => 'airtime',
            'amount' => $request->amount,
            'status' => 'pending',
            'reference' => uniqid('VTU-')
        ]);

        // 🚀 API CALL
        $response = Http::withHeaders([
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('VTPASS_BASE_URL').'/api/pay', [
            'request_id' => $tx->reference,
            'serviceID' => $network,
            'amount' => $request->amount,
            'phone' => $request->phone,
        ]);

        $result = $response->json();

        \Log::info('AIRTIME RESPONSE', $result ?? []);

        $desc = strtolower($result['response_description'] ?? '');
        $code = $result['code'] ?? null;

        // ✅ STRONG SUCCESS CHECK
        if ($code == '000' || str_contains($desc, 'successful')) {

            $tx->update(['status' => 'success']);

            DB::commit();

            return back()->with('success', 'Airtime sent successfully');
        }

        // ❌ refund
        $wallet->balance += $request->amount;
        $wallet->save();

        $tx->update(['status' => 'failed']);

        DB::commit();

        return back()->with('error', 'Failed → Refunded');

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error('AIRTIME ERROR', ['error' => $e->getMessage()]);

        return back()->with('error', 'System error, try again');
    }
}
}