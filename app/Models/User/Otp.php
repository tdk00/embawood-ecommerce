<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp',
        'expires_at',
    ];

    public $timestamps = false;

    protected $dates = [
        'expires_at',
    ];

    // Custom method to check if the OTP is expired
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
