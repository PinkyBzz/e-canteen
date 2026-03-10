<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warung extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /** Orders that contain at least one menu from this warung */
    public function orders()
    {
        return Order::whereHas('items.menu', function ($q) {
            $q->where('warung_id', $this->id);
        });
    }

    /** Today's revenue: completed order items for this warung */
    public function revenueToday(): float
    {
        return $this->revenueForPeriod(today(), today());
    }

    public function revenueThisMonth(): float
    {
        return $this->revenueForPeriod(now()->startOfMonth(), now()->endOfMonth());
    }

    public function revenueForPeriod($from, $to): float
    {
        return \App\Models\OrderItem::query()
            ->whereHas('menu', fn($q) => $q->where('warung_id', $this->id))
            ->whereHas('order', fn($q) => $q->where('status', 'completed')
                ->whereBetween('created_at', [$from, $to]))
            ->sum(\Illuminate\Support\Facades\DB::raw('price * quantity'));
    }
}
