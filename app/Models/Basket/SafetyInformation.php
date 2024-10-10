<?php

namespace App\Models\Basket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyInformation extends Model
{
    protected $table = 'safety_informations';

    protected $fillable = ['title', 'description', 'icon'];

    public function translations()
    {
        return $this->hasMany(SafetyInformationTranslation::class);
    }

    // Accessor to return the translated title
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->title : $this->attributes['title'];
    }

    // Accessor to return the translated description
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->description : $this->attributes['description'];
    }
}
