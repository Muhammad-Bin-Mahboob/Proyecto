<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\Size;
use App\Models\Review;
use App\Models\Order;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'brand_id',
        'image_url',
        'is_active',
        'offer_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}



