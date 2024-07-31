<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\SubIdeaItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubIdeaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubIdeaItem::create([
            'sub_idea_id' => 1,
            'title' => 'Sub Idea Item 1 for Sub Idea 1',
            'description' => 'Description for Sub Idea Item 1',
            'is_active' => true,
        ]);

        SubIdeaItem::create([
            'sub_idea_id' => 1,
            'title' => 'Sub Idea Item 2 for Sub Idea 1',
            'description' => 'Description for Sub Idea Item 2',
            'is_active' => true,
        ]);

        SubIdeaItem::create([
            'sub_idea_id' => 2,
            'title' => 'Sub Idea Item 1 for Sub Idea 2',
            'description' => 'Description for Sub Idea Item 1',
            'is_active' => true,
        ]);

        // Add more sub-idea items as needed
    }
}
