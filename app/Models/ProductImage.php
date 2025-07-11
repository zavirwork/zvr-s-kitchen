<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'products_id',
        'path',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
