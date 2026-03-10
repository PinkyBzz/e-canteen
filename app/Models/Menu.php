<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'warung_id',
        'name',
        'category',
        'price',
        'photo',
        'status',
        'description',
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
