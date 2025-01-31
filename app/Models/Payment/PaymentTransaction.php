<?php

namespace App\Models\Payment;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'password', 'amount', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
