<?php
// app/Models/AboutUs.php
namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $fillable = [];

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
}
