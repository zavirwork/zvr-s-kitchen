<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'price_at_time'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'order_item_addons')
            ->withPivot('price_at_time')
            ->withTimestamps();
    }
}
