<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', auth()->id())
            ->latest();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(6);

        return view('transactions.index', compact('transactions'));

//**********vtpass check*****************///
        if ($result['code'] == '000') {
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
    
}
