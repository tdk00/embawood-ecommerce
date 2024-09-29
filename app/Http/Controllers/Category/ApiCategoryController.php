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
        $category = Category::with('subcategories.products')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Gather products from subcategories
        $allProducts = $category->subcategories->flatMap(function ($subcategory) {
            return $subcategory->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'is_set' => $product->is_set,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'discount_ends_at' => $product->discount_ends_at,
                    'final_price' => $product->final_price,
                    'main_image' => url('storage/images/products/' . $product->main_image),
                    'average_rating' => $product->average_rating,
                    'is_in_basket' => $product->is_in_basket,
                    'is_favorite' => $product->is_favorite,
                    'remaining_discount_seconds' => $product->remaining_discount_seconds,
                    'has_unlimited_discount' => $product->has_unlimited_discount,
                    'has_limited_discount' => $product->has_limited_discount,
                    'badge' => url('storage/images/badge/' . $product->badge)
                ];
            });
        });

        return response()->json([
            'name' => $category->name,
            'description' => $category->description,
            'banner_image' => url('storage/images/category/banner/' . $category->banner_image),
            'product_count' => $allProducts->count(),
            'products' => $allProducts, // All products from subcategories
            'subcategories' => $category->subcategories->map(function ($subcategory) {
                return [
                    'category_id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'description' => $subcategory->description,
                    'banner_image' => url('storage/images/subcategories/banner/' . $subcategory->banner_image),
                    'products' => $subcategory->products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'is_set' => $product->is_set,
                            'price' => $product->price,
                            'discount' => $product->discount,
                            'discount_ends_at' => $product->discount_ends_at,
                            'final_price' => $product->final_price,
                            'main_image' => url('storage/images/products/' . $product->main_image),
                            'average_rating' => $product->average_rating,
                            'is_in_basket' => $product->is_in_basket,
                            'is_favorite' => $product->is_favorite,
                            'remaining_discount_seconds' => $product->remaining_discount_seconds,
                            'has_unlimited_discount' => $product->has_unlimited_discount,
                            'has_limited_discount' => $product->has_limited_discount,
                            'badge' => url('storage/images/badge/' . $product->badge)
                        ];
                    }),
                ];
            }),
        ]);
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

    public function get_homescreen_categories()
    {
        $homescreenCategories = Category::where('homescreen_widget', true)->get();

        $transformedHomescreenCategories = $homescreenCategories->map(function ($category) {
            $category->banner_image = url('storage/images/category/widget_images/' . $category->banner_image);
            $category->widget_view_image = url('storage/images/category/widget_images/' . $category->widget_view_image);
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'banner_image' => $category->banner_image,
                'widget_view_image' => $category->widget_view_image,
            ];
        });

        return response()->json($transformedHomescreenCategories);
    }
}
