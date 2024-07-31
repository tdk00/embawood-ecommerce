<?php

namespace App\Models\News;

use App\Models\HomeScreen\HomeScreenSlider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'banner_image', 'is_active'];

    public function sliders()
    {
        return $this->hasMany(HomeScreenSlider::class);
    }
}
