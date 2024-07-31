<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\IdeaWidgetItem;
use App\Models\Ideas\IdeaWidgetTab;
use App\Models\Ideas\SubIdea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdeaWidgetItemSeeder extends Seeder
{
    public function run()
    {
        $tabs = IdeaWidgetTab::all();

        foreach ($tabs as $tab) {
            $subIdeas = SubIdea::where('idea_id', $tab->idea_id)->get();

            foreach ($subIdeas as $index => $subIdea) {
                IdeaWidgetItem::create([
                    'idea_widget_tab_id' => $tab->id,
                    'sub_idea_id' => $subIdea->id,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
