<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Offer extends Model
{
    protected $fillable = [
        'name', 'description', 'discount', 'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

