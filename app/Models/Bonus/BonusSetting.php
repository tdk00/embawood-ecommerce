<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSetting extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'target_count', 'bonus_amount', 'period'];
}
