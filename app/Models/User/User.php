<?php

namespace App\Models\User;

use App\Models\Account\UserDeliveryAddress;
use App\Models\Bonus\BonusExecution;
use App\Models\Checkout\Order;
use App\Models\Discount\Coupon;
use App\Models\Discount\UserCoupon;
use App\Models\Product\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'phone_verified_at',
        'password',
        'total_bonus_amount',
        'used_bonus_amount',
        'remaining_bonus_amount',
        'last_product_view_bonus_achieved_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_product_view_bonus_achieved_at' => 'datetime',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bonusExecutions()
    {
        return $this->hasMany(BonusExecution::class);
    }

    public function deliveryAddresses()
    {
        return $this->hasMany(UserDeliveryAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'user_coupons')->withPivot('earned_amount', 'is_used');
    }
}
