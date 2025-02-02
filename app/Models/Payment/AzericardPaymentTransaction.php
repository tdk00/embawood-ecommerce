<?php

namespace App\Models\Payment;

use App\Models\Checkout\Order;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AzericardPaymentTransaction extends Model
{
    protected $fillable = ['order_id', 'user_id', 'amount', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Generate a new formatted order_id
            $latestOrderId = AzericardPaymentTransaction::max('order_id') ?? 0;
            $transaction->order_id = str_pad($latestOrderId + 1, 9, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'azericard_payment_transaction_id');
    }
}
