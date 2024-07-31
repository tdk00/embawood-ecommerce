<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\TopList;
use Illuminate\Http\Request;

class ApiTopListController extends Controller
{
    public function index($subcategoryId)
    {
        $topList = TopList::where('subcategory_id', $subcategoryId)
            ->orderBy('position')
            ->with('product')
            ->get();

        return response()->json($topList);
    }
}
