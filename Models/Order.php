<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\Size;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'size_id', 'quantity', 'total', 'status', 'shipping_address', 'tracking_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
