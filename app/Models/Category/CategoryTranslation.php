<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $fillable = [
        'category_id',
        'locale',
        'name',
        'description',
        'meta_title',
        'meta_description',
        'description_web'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
