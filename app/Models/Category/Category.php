<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'banner_image',
        'widget_view_image',
        'homescreen_widget',
        'description',
        'is_active',
        'slug'
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    // Accessor to return the translated name
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->name : $this->attributes['name'];
    }

    // Accessor to return the translated description
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->description : $this->attributes['description'];
    }

    // Accessor to return the translated meta title
    public function getMetaTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->meta_title : null;
    }

    // Accessor to return the translated meta description
    public function getMetaDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->meta_description : null;
    }

    // Accessor to return the translated description for the web
    public function getDescriptionWebAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->description_web : null;
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function topList()
    {
        return $this->hasMany(TopList::class)->orderBy('position');
    }
}
