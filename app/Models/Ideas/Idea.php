<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_category_view',
        'title_homepage_tab_view',
        'is_active',
    ];

    public function subIdeas()
    {
        return $this->hasMany(SubIdea::class);
    }

    public function translations()
    {
        return $this->hasMany(IdeaTranslation::class);
    }

    public function getTitleCategoryViewAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->title_category_view : $this->attributes['title_category_view'];
    }

    public function getTitleHomepageTabViewAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->title_homepage_tab_view : $this->attributes['title_homepage_tab_view'];
    }
}
