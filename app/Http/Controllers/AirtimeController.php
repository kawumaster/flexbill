<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\VtpassService;


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
        'mtn' => ['0913','0801','0803','0806','0813','0816','0810','0814','0903','0906','0703','0706','0704','07025','07026'],
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

public function buyAirtime(Request $request, VtpassService $vtpass)
{
    $request->validate([
        'phone' => 'required|digits:11',
        'amount' => 'required|numeric|min:100'
    ]);

    $network = $this->detectNetwork($request->phone);

    if (!$network) {
        return back()->with('error', 'Invalid phone number');
    }

    $user = auth()->user();

    DB::beginTransaction();

    try {

        $wallet = $user->wallet()->lockForUpdate()->first();

        if ($wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance');
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        $tx = Transaction::create([
            'user_id' => $user->id,
            'type' => 'airtime',
            'amount' => $request->amount,
            'status' => 'pending',
            'reference' => uniqid('VTU-')
        ]);

        // 🔹 call service
        $result = $vtpass->buyAirtime(
            $tx->reference,
            $request->phone,
            $network,
            $request->amount
        );

        $desc = strtolower($result['response_description'] ?? '');

        if (str_contains($desc, 'successful')) {

            $tx->update(['status' => 'success']);

            DB::commit();

            return back()->with('success', 'Airtime sent');
        }

        // refund
        $wallet->balance += $request->amount;
        $wallet->save();

        $tx->update(['status' => 'failed']);

        DB::commit();

        return back()->with('error', 'Failed → Refunded');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'System error');
    }
}
}