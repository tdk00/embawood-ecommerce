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

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function subIdeaItems()
    {
        return $this->hasMany(SubIdeaItem::class);
    }
}
