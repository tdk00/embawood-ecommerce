<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\IdeaWidgetItem;
use Illuminate\Http\Request;

class ApiIdeaWidgetItemController extends Controller
{
    public function index()
    {
        $items = IdeaWidgetItem::with('subIdea')->orderBy('sort_order')->get();
        return response()->json($items);
    }
}
