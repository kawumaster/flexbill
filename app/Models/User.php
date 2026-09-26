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
        'phone',
    ];



    /**
     * Automatically create wallet after user registration
     */
    protected static function booted()
    {
        static::created(function ($user) {
            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );
        });
    }



    /**
     * Wallet Relationship
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }


    

    /**
     * Transactions Relationship
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Virtual Account Relationship
     */
    public function virtualAccount()
    {
        return $this->hasOne(\App\Models\VirtualAccount::class);
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