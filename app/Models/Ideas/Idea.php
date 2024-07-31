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
}
