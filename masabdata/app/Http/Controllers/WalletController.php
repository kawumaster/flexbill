<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function addMoneyForm()
    {
        return view('wallet.add-money');
    }

    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
        ]);

        $wallet = auth()->user()->wallet;

        // Increase wallet balance
        $wallet->increment('balance', $request->amount);

        // Log transaction
        Transaction::create([
            'user_id' => auth()->id(),
            'type' => 'funding',
            'amount' => $request->amount,
            'status' => 'successefull',
            'reference' => 'FUND-' . Str::upper(Str::random(10)),
            'details' => json_encode([
                'method' => 'manual',
            ]),
        ]);

        return redirect()->route('dashboard')->with('success', 'Wallet funded successfully');
    }
}

