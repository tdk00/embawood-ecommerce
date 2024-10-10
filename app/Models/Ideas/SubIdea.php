<?php

namespace App\Models\Ideas;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIdea extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'title',
        'image_category_view',
        'image_homepage_tab_view',
        'is_active',
    ];

    public function translations()
    {
        return $this->hasMany(SubIdeaTranslation::class);
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->title : $this->attributes['title'];
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function subIdeaItems()
    {
        return $this->hasMany(SubIdeaItem::class);
    }
}
