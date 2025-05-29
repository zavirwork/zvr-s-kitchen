<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $id = 'id';
    protected $fillable = [
        'customer_name',
        'customer_whatsapp',
        'message',
        'evidence_transfer',
        'total_price',
        'status',
        'latitude',
        'longitude',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
