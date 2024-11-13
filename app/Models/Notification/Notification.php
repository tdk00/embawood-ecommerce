<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'message', 'status', 'sent_at'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
