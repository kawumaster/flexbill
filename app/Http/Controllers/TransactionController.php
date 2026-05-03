<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // 📜 HISTORY
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        return view('transactions.index', compact('transactions'));


        //**********vtpass check*****************///
        if($result['code'] == '000') {
    $transaction->update([
        'status' => 'success',
        'processed' => true
    ]);
} else {
    $transaction->update([
        'status' => 'failed'
    ]);
}
    }

    // 🧾 RECEIPT
    public function show($reference)
    {
        $tx = Transaction::where('reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('transactions.show', compact('tx'));
    }
    
    
}
