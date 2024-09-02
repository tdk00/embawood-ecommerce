<?php

namespace App\Models\Discount;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount_percentage', 'usage_limit', 'description', 'min_required_amount', 'max_required_amount',
        'start_date', 'end_date', 'usage_count', 'is_active'
    ];

    protected $casts = [
        'description' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withTimestamps();
    }

    public function usedCoupons()
    {
        return $this->hasMany(UsedCoupon::class);
    }
}
