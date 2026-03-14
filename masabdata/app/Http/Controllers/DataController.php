<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPlan;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Service;

class DataController extends Controller
{
    
    

    //
   public function index()
{
    $plans = DataPlan::all();
    return view('data.index', compact('plans'));
}
   
    // data logic

public function buy(Request $request)
{
    $request->validate([
        'phone' => 'required|min:11|max:11',
        'plan_id' => 'required|exists:data_plans,id',
    ]);

    $plan = DataPlan::findOrFail($request->plan_id);
    $wallet = auth()->user()->wallet;

    if ($wallet->balance < $plan->price) {
        return back()->with('error', 'Insufficient wallet balance');
    }

    $wallet->decrement('balance', $plan->price);

    Transaction::create([
        'user_id' => auth()->id(),
        'type' => 'data',
        'amount' => $plan->price,
        'status' => 'success',
        'reference' => 'DATA-' . Str::upper(Str::random(10)),
    ]);

    return back()->with('success', 'Data purchase successful');

    // services 


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


////////////////////////////////////////////
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
