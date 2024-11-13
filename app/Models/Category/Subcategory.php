<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'widget_view_image', 'homescreen_widget',
        'banner_image', 'description', 'image', 'is_active', 'slug'
    ];

    protected $casts = [
        'homescreen_widget' => 'boolean',
    ];

    protected $appends = [
        'full_widget_view_image', 'meta_title', 'meta_description', 'description_web'
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    public function translations()
    {
        return $this->hasMany(SubcategoryTranslation::class);
    }

    // Accessors for translations
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->name : $this->attributes['name'];
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->description : $this->attributes['description'];
    }

    public function getMetaTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->meta_title : null;
    }

    public function getMetaDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->meta_description : null;
    }

    public function getDescriptionWebAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->description_web : null;
    }

    public function getFullWidgetViewImageAttribute()
    {
        return url(Storage::url('images/category/' . $this->attributes['widget_view_image']));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
