<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['badge_image', 'is_active'];

    public static function getActiveBadge()
    {
        return self::where('is_active', true)->first();
    }
}
