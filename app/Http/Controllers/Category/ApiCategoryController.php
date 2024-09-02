<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Ideas\Idea;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    public function index()
    {
        return Category::with('subcategories')->get();
    }

    public function show($id)
    {
        return Category::with('subcategories')->find($id);
    }

    public function getCategoriesWithSubcategory()
    {
        $categories = Category::with('subcategories')->get();
        $ideas = Idea::with('subideas')->get();

        $transformedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'banner_image' => url('storage/images/category/banner/' . $category->banner_image),
                'subcategories' => $category->subcategories->map(function ($subcategory) {
                    $subcategory->image = url('storage/images/subcategories/' . $subcategory->image);
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                        'image' => $subcategory->image,
                    ];
                }),
            ];
        });

        $transformedIdeas = $ideas->map(function ($idea) {
            return [
                'id' => $idea->id,
                'name' => $idea->title_category_view,
                'subideas' => $idea->subideas->map(function ($subidea) {
                    $subidea->image_category_view = url('storage/images/ideas/' . $subidea->image_category_view);
                    return [
                        'id' => $subidea->id,
                        'name' => $subidea->title,
                        'image' => $subidea->image_category_view,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => 'success',
            'categories' => $transformedCategories,
            'ideas' => $transformedIdeas
        ]);


    }
}
