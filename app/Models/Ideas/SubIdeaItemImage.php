<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIdeaItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_idea_item_id',
        'image_url',
    ];

    public function subIdeaItem()
    {
        return $this->belongsTo(SubIdeaItem::class);
    }
}
