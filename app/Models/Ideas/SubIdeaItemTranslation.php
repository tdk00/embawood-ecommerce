<?php

namespace App\Models\Ideas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIdeaItemTranslation extends Model
{
    protected $fillable = [
        'locale',
        'title',
        'description',
    ];

    public function subIdeaItem()
    {
        return $this->belongsTo(SubIdeaItem::class);
    }
}
