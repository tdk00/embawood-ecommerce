<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\SubIdea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubIdeaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubIdea::create([
            'idea_id' => 1,
            'title' => 'Sub Idea 1 for Idea 1',
            'image_category_view' => 'image1_category.jpg',
            'image_homepage_tab_view' => 'image1_homepage.jpg',
        ]);

        SubIdea::create([
            'idea_id' => 1,
            'title' => 'Sub Idea 2 for Idea 1',
            'image_category_view' => 'image2_category.jpg',
            'image_homepage_tab_view' => 'image2_homepage.jpg',
        ]);

        SubIdea::create([
            'idea_id' => 2,
            'title' => 'Sub Idea 1 for Idea 2',
            'image_category_view' => 'image3_category.jpg',
            'image_homepage_tab_view' => 'image3_homepage.jpg',
        ]);

        // Add more sub-ideas as needed
    }
}
