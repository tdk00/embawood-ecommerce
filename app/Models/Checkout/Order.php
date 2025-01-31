<?php

namespace App\Models\Checkout;

use App\Models\Discount\Coupon;
use App\Models\Payment\PaymentTransaction;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total', 'status', 'address', 'used_bonus', 'bonus_discount', 'coupon_discount', 'item_discounts_total', 'online_payment_id', 'payment_transaction_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'order_coupons');
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}
