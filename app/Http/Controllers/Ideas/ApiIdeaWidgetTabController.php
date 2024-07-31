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
                $query->limit(4)->with([
                    'subIdea'
                ]);
            }
        ])->orderBy('sort_order')->get();

        return response()->json($tabs);
    }
}
