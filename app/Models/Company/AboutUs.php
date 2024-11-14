<?php
// app/Models/AboutUs.php
namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = ['banner_image', 'description_web']; // Add new fields here

    public function translations()
    {
        return $this->hasMany(AboutUsTranslation::class);
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->title : "";
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->description : "";
    }

    // Optionally, add accessor for description_web if translations are needed
    public function getDescriptionWebAttribute($value)
    {
        // Check if translations are necessary, if so, retrieve from translations table
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->description_web : $value;
    }
}
