<?php

namespace App\Models\Ideas;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIdeaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_idea_id',
        'title',
        'description',
        'is_active',
    ];

    public function subIdea()
    {
        return $this->belongsTo(SubIdea::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sub_idea_item');
    }

    public function images()
    {
        return $this->hasMany(SubIdeaItemImage::class);
    }
}
