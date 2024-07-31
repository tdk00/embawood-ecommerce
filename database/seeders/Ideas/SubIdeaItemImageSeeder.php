<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\SubIdeaItemImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubIdeaItemImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubIdeaItemImage::create([
            'sub_idea_item_id' => 1,
            'image_url' => 'image1_slider1.jpg',
        ]);

        SubIdeaItemImage::create([
            'sub_idea_item_id' => 1,
            'image_url' => 'image1_slider2.jpg',
        ]);

        SubIdeaItemImage::create([
            'sub_idea_item_id' => 2,
            'image_url' => 'image2_slider1.jpg',
        ]);

        SubIdeaItemImage::create([
            'sub_idea_item_id' => 3,
            'image_url' => 'image3_slider1.jpg',
        ]);

        // Add more images as needed
    }
}
