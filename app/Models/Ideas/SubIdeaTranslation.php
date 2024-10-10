<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIdeaTranslation extends Model
{
    protected $fillable = [
        'locale',
        'title',
    ];

    public function subIdea()
    {
        return $this->belongsTo(SubIdea::class);
    }
}
