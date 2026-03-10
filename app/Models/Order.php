<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'break_time',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getBreakTimeLabelAttribute(): string
    {
        return $this->break_time === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Menunggu',
            'preparing'  => 'Sedang Disiapkan',
            'ready'      => 'Siap Diambil',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'preparing'  => 'info',
            'ready'      => 'success',
            'completed'  => 'secondary',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_code = 'ORD-' . strtoupper(uniqid());
        });
    }
}
