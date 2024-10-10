<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSettingTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['bonus_setting_id', 'locale', 'title', 'description'];
}
