<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\Subcategory;
use Illuminate\Http\Request;

class ApiSubcategoryController extends Controller
{
    public function index()
    {
        return Subcategory::get();
    }

    public function show(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'sort_by' => 'in:price,name',
            'sort_direction' => 'in:asc,desc,default',
            'per_page' => 'integer|min:1|max:100',
            'id' => 'required|integer'
        ]);

        // Fetch the subcategory with its category
        $subcategory = Subcategory::with('category')
            ->find($request->id);

        // Check if subcategory exists
        if (!$subcategory) {
            return response()->json([
                'error' => 'Category or Subcategory not found',
            ], 404);
        }

        // Prepare subcategory data
        $subcategory->banner = url('storage/images/subcategories/banner/' . $subcategory->banner_image);
        $subCategoryData = [
            'category_id' => $subcategory->category_id,
            'name' => $subcategory->name,
            'description' => $subcategory->description,
            'banner_image' => $subcategory->banner_image,
        ];

        // Fetch products associated with the subcategory
        $productsQuery = $subcategory->products();

        // Apply sorting
        if ($request->has('sort_by') && $request->get('sort_direction', 'asc') != "default" ) {
            $productsQuery->orderBy($request->get('sort_by'), $request->get('sort_direction', 'asc'));
        }

        // Retrieve all products and transform product data
        $products = $productsQuery->get()->map(function ($product) {
            $product->image = url('storage/images/products/' . $product->main_image);
            $product->image_hover = url('storage/images/products/' . $product->hover_image);
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
                'has_limited_discount' => $product->has_limited_discount
            ];
        });

        // Prepare response data
        $response = [
            'subcategory' => $subCategoryData,
            'product_count' => $products->count(),
            'products' => $products,
        ];

        // Return the response as JSON
        return response()->json($response);
    }

    public function get_homescreen_subcategories()
    {
        $homescreenSubcategories = Subcategory::where('homescreen_widget', true)->get();

        $transformedHomescreenSubcategories = $homescreenSubcategories->map(function ($subcategory) {
            $subcategory->image = url('storage/images/subcategories/' . $subcategory->image);
            $subcategory->banner_image = url('storage/images/subcategories/' . $subcategory->banner_image);
            $subcategory->widget_view_image = url('storage/images/subcategories/' . $subcategory->widget_view_image);
            return [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'description' => $subcategory->description,
                'image' => $subcategory->image,
                'banner_image' => $subcategory->banner_image,
                'widget_view_image' => $subcategory->widget_view_image,
                'is_popular' => $subcategory->is_popular,
            ];
        });

        return response()->json($transformedHomescreenSubcategories);
    }
}
