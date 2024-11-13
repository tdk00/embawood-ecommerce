<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCallRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp_number',
        'subject',
        'address',
        'status',
    ];
}
