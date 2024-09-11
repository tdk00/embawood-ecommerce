<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopList extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'product_id', 'position'];  // Changed subcategory_id to category_id

    public function category()
    {
        return $this->belongsTo(Category::class);  // Changed to Category relationship
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
