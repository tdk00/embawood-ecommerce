<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSetting extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'type', 'target_count', 'bonus_amount', 'period'];

    public function translations()
    {
        return $this->hasMany(BonusSettingTranslation::class);
    }

    // Accessor for translated title
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->title : $this->attributes['title'];
    }

    // Accessor for translated description
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->description : $this->attributes['description'];
    }
}
