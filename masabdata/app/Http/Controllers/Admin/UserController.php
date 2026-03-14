<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //                                           
    

public function index()
{
    return view('admin.users', [
        'users' => User::latest()->paginate(10)
    ]);
}

}
