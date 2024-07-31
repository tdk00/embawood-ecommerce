<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    public function index()
    {
        return Category::with('subcategories')->get();
    }

    public function show($id)
    {
        return Category::with('subcategories')->findOrFail($id);
    }
}
