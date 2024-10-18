<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'banner_image', 'widget_view_image', 'homescreen_widget', 'description', 'is_active' ];


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

        // Return the translation if available, otherwise default to the original name
        return $translation ? $translation->name : $this->attributes['name'];
    }

    // Accessor to return the translated description
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->description : $this->attributes['description'];
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
