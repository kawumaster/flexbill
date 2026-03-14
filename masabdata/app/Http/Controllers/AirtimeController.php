<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;

class AirtimeController extends Controller
{
    public function index()
    {
        return view('airtime.buy');
    }

    public function buy(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'network' => 'required',
            'amount' => 'required|numeric|min:50',
        ]);

        $wallet = auth()->user()->wallet;

        if ($wallet->balance < $request->amount) {
            // return back()->withErrors([
            //     'amount' => 'Insufficient wallet balance'
                return back()->with('error', 'Insufficient wallet balance');
            // ]);

        }



        // Deduct wallet
        $wallet->decrement('balance', $request->amount);

        // Save transaction (Phase 8.5 structure)
        Transaction::create([
            'user_id' => auth()->id(),
            'type' => 'airtime',
            'amount' => $request->amount,
            'status' => 'success',
            'reference' => 'AIR-' . Str::upper(Str::random(10)),
            'details' => json_encode([
                'phone' => $request->phone,
                'network' => $request->network,
            ]),
        ]);

        return back()->with('success', 'Airtime purchase successful'); 
        // redirect()
        //     ->route('dashboard');



        ////// services////////////



$service = Service::where('name', 'airtime')->first();

if (!$service || !$service->active) {
    return back()->with('error', 'Service unavailable');
}

$amount = $request->amount; // what user enters

// Calculate profit
if ($service->profit_type === 'percentage') {
    $profit = ($service->profit_value / 100) * $amount;
} else {
    $profit = $service->profit_value;
}

// Provider cost
$provider_amount = $amount - $profit;

// Deduct FULL amount from wallet
$user->wallet->balance -= $amount;
$user->wallet->save();

// Save transaction
Transaction::create([
    'user_id' => $user->id,
    'type' => 'airtime',
    'amount' => $amount,
    'profit' => $profit,
    'status' => 'success',
    'reference' => 'VTU-' . uniqid(),
]);
            
    }
}
