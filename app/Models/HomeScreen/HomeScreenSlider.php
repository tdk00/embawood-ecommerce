<?php

namespace App\Models\HomeScreen;

use App\Models\News\News;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeScreenSlider extends Model
{
    use HasFactory;

    protected $appends = ['full_slider_image'];

    public function getFullSliderImageAttribute()
    {
        return url(Storage::url('images/home_screen/sliders/' . $this->attributes['slider_image']));
    }

    protected $fillable = ['news_id', 'slider_image', 'is_active'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
