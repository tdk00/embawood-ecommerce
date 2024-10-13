<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\IdeaWidgetTab;
use Illuminate\Http\Request;

class ApiIdeaWidgetTabController extends Controller
{
    public function index()
    {
        $tabs = IdeaWidgetTab::with([
            'idea',
            'ideaWidgetItems' => function ($query) {
                $query->with(['subIdea']);
            }
        ])->orderBy('sort_order')->get();

        // Map through each tab to add the full image URL for each subIdea
        $tabs->map(function ($tab) {
            $tab->ideaWidgetItems->map(function ($item) {
                if (isset($item->subIdea->image_homepage_tab_view)) {
                    $item->subIdea->image_homepage_tab_view = url('storage/images/ideas/' . $item->subIdea->image_homepage_tab_view);
                }
                return $item;
            });
            return $tab;
        });

        return response()->json($tabs);
    }
}
