<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\SubIdeaItem;
use Illuminate\Http\Request;

class ApiSubIdeaItemController extends Controller
{

    public function index()
    {
        $subIdeaItems = SubIdeaItem::with(['products', 'images'])->get();
        return response()->json($subIdeaItems);
    }

    public function show(SubIdeaItem $subIdeaItem)
    {
        $subIdeaItem->load(['products', 'images']);
        return response()->json($subIdeaItem);
    }
}
