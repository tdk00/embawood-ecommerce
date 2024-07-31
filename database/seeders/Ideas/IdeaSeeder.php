<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\Idea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdeaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Idea::create([
            'title_category_view' => 'Category Idea 1',
            'title_homepage_tab_view' => 'Homepage Idea 1',
        ]);

        Idea::create([
            'title_category_view' => 'Category Idea 2',
            'title_homepage_tab_view' => 'Homepage Idea 2',
        ]);

        // Add more ideas as needed
    }
}
