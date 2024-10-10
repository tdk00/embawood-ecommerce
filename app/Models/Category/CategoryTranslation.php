<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'locale', 'name', 'description'];

    // Define the inverse relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
