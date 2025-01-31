<?php

namespace App\Models\Checkout;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'set_id', 'quantity', 'price', 'discount_percentage', 'discount_amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withoutGlobalScope('active');
    }

    public function set()
    {
        return $this->belongsTo(Product::class, 'set_id');
    }

    public function getFinalPriceAttribute()
    {
        $discount = $this->discount_percentage ?? 0;
        return $this->price * (1 - $discount / 100);
    }
}
