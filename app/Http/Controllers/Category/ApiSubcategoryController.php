<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Subcategory;
use Illuminate\Http\Request;

class ApiSubcategoryController extends Controller
{
    public function index()
    {
        return Subcategory::get();
    }

    public function show($id)
    {
        return Subcategory::with('products')->findOrFail($id);
    }

    public function get_homescreen_subcategories()
    {
        return Subcategory::where('homescreen_widget', true)->get();
    }
}
