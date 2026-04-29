<?php

namespace App\Models;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // 💰 Wallet relation
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    // 💳 Transactions relation
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}