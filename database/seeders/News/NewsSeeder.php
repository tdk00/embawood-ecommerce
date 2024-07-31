<?php

namespace Database\Seeders\News;

use App\Models\News\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run()
    {
        News::factory()->count(10)->create();
    }
}
