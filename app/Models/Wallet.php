<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Wallet;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


// Route::post('/wallet/add', function (Request $request) {
//     $wallet = Auth::user()->wallet;
//     $wallet->balance += $request->amount;
//     $wallet->save();

//     return redirect()->route('dashboard')->with('success', 'Money added successfully!');
// })->middleware('auth')->name('wallet.add');
