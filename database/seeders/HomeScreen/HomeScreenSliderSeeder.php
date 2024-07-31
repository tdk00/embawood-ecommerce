<?php

namespace Database\Seeders\HomeScreen;

use App\Models\HomeScreen\HomeScreenSlider;
use App\Models\News\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeScreenSliderSeeder extends Seeder
{
    public function run()
    {
        $newsItems = News::all();

        foreach ($newsItems as $news) {
            HomeScreenSlider::create([
                'news_id' => $news->id,
                'slider_image' => null,
                'is_active' => true,
            ]);
        }
    }
}
