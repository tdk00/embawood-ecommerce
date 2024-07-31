<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaWidgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_widget_tab_id',
        'sub_idea_id',
        'sort_order',
    ];

    public function ideaWidgetTab()
    {
        return $this->belongsTo(IdeaWidgetTab::class);
    }

    public function subIdea()
    {
        return $this->belongsTo(SubIdea::class);
    }
}
