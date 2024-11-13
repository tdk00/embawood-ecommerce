<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImageTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['product_image_id', 'locale', 'alt_text'];

    public function productImage()
    {
        return $this->belongsTo(ProductImage::class);
    }
}
