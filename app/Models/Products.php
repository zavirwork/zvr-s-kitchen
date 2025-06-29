<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $id = 'id';
    protected $fillable = [
        'name',
        'type',
        'description',
        'price',
        'stock',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'product_addons', 'product_id', 'addon_id');
    }
}
