<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $table = 'product_translations'; // Specify the table name

    protected $fillable = ['product_id', 'locale', 'name', 'description', 'short_description', 'meta_title', 'meta_description', 'description_web'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withoutGlobalScope('active');
    }
}
