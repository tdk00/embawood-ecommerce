<?php

namespace App\Models\Discount;

use App\Models\Checkout\Order;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedCoupon extends Model
{
    protected $fillable = ['coupon_id', 'user_id', 'order_id'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
