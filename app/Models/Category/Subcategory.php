<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name',  'widget_view_image', 'homescreen_widget', 'banner_image',  'description', 'image', 'is_active'];
    protected $casts = [
        'homescreen_widget' => 'boolean',
    ];

    protected $appends = ['full_widget_view_image'];

    public function translations()
    {
        return $this->hasMany(SubcategoryTranslation::class);
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
