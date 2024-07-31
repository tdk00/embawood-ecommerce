<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name',  'widget_view_image', 'homescreen_widget',  'description', 'image', 'is_active'];
    protected $casts = [
        'homescreen_widget' => 'boolean',
    ];

    protected $appends = ['full_widget_view_image'];

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

    public function topList()
    {
        return $this->hasMany(TopList::class);
    }

}
