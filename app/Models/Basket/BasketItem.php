<?php

namespace App\Models\Basket;

use App\Models\Product\Product;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasketItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'set_id', 'quantity', 'identifier'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withoutGlobalScope('active');
    }

    public function set()
    {
        return $this->belongsTo(Product::class, 'set_id');
    }
}
