<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\TransactionController;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// services

Route::middleware(['auth'])->group(function () {

    Route::get('/add-money', [WalletController::class, 'addMoneyForm'])->name('add-money');
    Route::post('/add-money', [WalletController::class, 'addMoney'])->name('wallet.add');

});

//airtime


Route::middleware(['auth'])->group(function () {
    Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime');
    Route::post('/airtime/buy', [AirtimeController::class, 'buy'])->name('airtime.buy');
});



//data

Route::middleware(['auth'])->group(function () {
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::post('/data/buy', [DataController::class, 'buy'])->name('data.buy');
});

//'''''end data''''''''///

//''''transaction histry''''//

Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
});

//'''''admin'''''
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

//'''''admindashbord'''''////
Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');

// user admin dashh
Route::get('/users',[UserController::class,'index'])->name('admin.users');


//'''''''''''''''''''''///
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    

    Route::get('/tv', function () {
        return view('services.tv');
    })->name('tv');

    Route::get('/electricity', function () {
        return view('services.electricity');
    })->name('electricity');

   


});


Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', function () {
        return view('wallet.add-money');
    })->name('wallet.page');

    Route::post('/wallet/add-money', [WalletController::class, 'addMoney'])->name('wallet.add');

   
    
});



require __DIR__.'/auth.php';
