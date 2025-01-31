<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image_path', 'is_main', 'order'
    ];


    protected $appends = ['full_image'];

    public function translations()
    {
        return $this->hasMany(ProductImageTranslation::class);
    }

    public function getTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    public function getAltTextAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        // If a translation exists for the current locale, return it; otherwise, return a fallback
        return $translation ? $translation->alt_text : null;
    }

    public function getFullImageAttribute()
    {
        return url(Storage::url('images/products/' . $this->attributes['image_path']));
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withoutGlobalScope('active');
    }
}
