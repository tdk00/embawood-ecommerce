<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaTranslation extends Model
{
    protected $fillable = [
        'locale',
        'title_category_view',
        'title_homepage_tab_view',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}
