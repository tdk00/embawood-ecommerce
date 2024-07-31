<?php

namespace App\Models\Discount;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'discount_percentage', 'coupon_min', 'coupon_max', 'start_date', 'expiration_date'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_coupons')->withPivot('earned_amount', 'is_used');
    }
}
