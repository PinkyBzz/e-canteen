<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWarung(): bool
    {
        return $this->role === 'warung';
    }

    public function warung()
    {
        return $this->hasOne(Warung::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function balanceHistories()
    {
        return $this->hasMany(BalanceHistory::class);
    }
}
