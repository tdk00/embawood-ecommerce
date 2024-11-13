<?php

namespace App\Models\News;

use App\Models\HomeScreen\HomeScreenSlider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'banner_image', 'is_active', 'slug'];

    public function sliders()
    {
        return $this->hasMany(HomeScreenSlider::class);
    }

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class);
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->title : $this->attributes['title'];
    }

    public function getContentAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->content : $this->attributes['content'];
    }

    public function getMetaTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->meta_title : null;
    }

    // Accessor for meta_description
    public function getMetaDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->meta_description : null;
    }

    public function getContentWebAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->content_web : null;
    }
}
