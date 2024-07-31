<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaWidgetTab extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'sort_order',
    ];

    public function ideaWidgetItems()
    {
        return $this->hasMany(IdeaWidgetItem::class)->orderBy('sort_order');
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}
