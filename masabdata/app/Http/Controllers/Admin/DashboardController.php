<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;


class DashboardController extends Controller
{
    //


public function index()
{
    return view('admin.dashboard', [
        'users' => User::count(),
        'transactions' => Transaction::count(),
        'revenue' => Transaction::where('status','success')->sum('amount'),
    ]);
}

}
