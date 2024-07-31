<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\Idea;
use App\Models\Ideas\IdeaWidgetTab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdeaWidgetTabSeeder extends Seeder
{
    public function run()
    {
        $ideas = Idea::all();

        foreach ($ideas as $index => $idea) {
            IdeaWidgetTab::create([
                'idea_id' => $idea->id,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
