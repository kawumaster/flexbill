<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;
        return view('dashboard', compact('wallet'));
    }
}


