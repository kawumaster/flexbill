<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    // =========================
    // 💰 SHOW FUND WALLET FORM
    // =========================
    public function addMoneyForm()
    {
        return view('wallet.add-money');
    }
}