<?php

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'fullname', 'phone', 'address_line1', 'address_line2', 'city', 'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
