<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price'];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_addons', 'addon_id', 'product_id');
    }
}
