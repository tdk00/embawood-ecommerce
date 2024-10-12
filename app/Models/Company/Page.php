<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['show_in_footer', 'title', 'content'];

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->title : $this->attributes['title'];
    }

    public function getContentAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->content : $this->attributes['content'];
    }
}
