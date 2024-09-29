<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image_path', 'is_main'
    ];


    protected $appends = ['full_image'];

    public function getFullImageAttribute()
    {
        return url(Storage::url('images/products/' . $this->attributes['image_path']));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
