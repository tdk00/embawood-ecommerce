<?php

namespace App\Models\Support;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneCallRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
