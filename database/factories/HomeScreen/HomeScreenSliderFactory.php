<?php

namespace Database\Factories\HomeScreen;

use App\Models\HomeScreen\HomeScreenSlider;
use App\Models\News\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HomeScreen\HomeScreenSlider>
 */
class HomeScreenSliderFactory extends Factory
{
    protected $model = HomeScreenSlider::class;

    public function definition()
    {
        return [
            'news_id' => News::factory(),
            'slider_image' => null,
            'is_active' => $this->faker->boolean,
        ];
    }
}
