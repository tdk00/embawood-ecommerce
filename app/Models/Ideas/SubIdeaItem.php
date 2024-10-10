<?php

namespace App\Models\Ideas;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIdeaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_idea_id',
        'title',
        'description',
        'is_active',
    ];

    public function translations()
    {
        return $this->hasMany(SubIdeaItemTranslation::class);
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->title : $this->attributes['title'];
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->description : $this->attributes['description'];
    }

    public function subIdea()
    {
        return $this->belongsTo(SubIdea::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sub_idea_item');
    }

    public function images()
    {
        return $this->hasMany(SubIdeaItemImage::class);
    }
}
