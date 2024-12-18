<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\Subcategory;
use Illuminate\Http\Request;

class ApiSubcategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subcategory-details",
     *     operationId="getSubcategoryDetails",
     *     tags={"Subcategories"},
     *     summary="Retrieve details of a specific subcategory",
     *     description="Fetches subcategory details along with its associated products. You can filter by `id` or `slug`, sort the products, and paginate the results.",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=false,
     *         description="Subcategory ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="slug",
     *         in="query",
     *         required=false,
     *         description="Subcategory slug",
     *         @OA\Schema(type="string", example="living-room-furniture")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         required=false,
     *         description="Sort products by a specific field",
     *         @OA\Schema(type="string", enum={"price", "name"}, example="price")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         required=false,
     *         description="Sort direction for the products",
     *         @OA\Schema(type="string", enum={"asc", "desc", "default"}, example="asc")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subcategory details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="subcategory",
     *                 type="object",
     *                 description="Details of the subcategory",
     *                 @OA\Property(property="category_id", type="integer", description="Category ID", example=1),
     *                 @OA\Property(property="name", type="string", description="Subcategory name", example="Living Room"),
     *                 @OA\Property(property="slug", type="string", description="Subcategory slug", example="living-room"),
     *                 @OA\Property(property="description", type="string", description="Subcategory description", example="Furniture for your living room."),
     *                 @OA\Property(property="meta_title", type="string", description="Meta title for SEO", example="Living Room Furniture"),
     *                 @OA\Property(property="meta_description", type="string", description="Meta description for SEO", example="Best furniture for living rooms."),
     *                 @OA\Property(property="description_web", type="string", description="Web description for the subcategory", example="Browse our premium furniture selection."),
     *                 @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/subcategories/banner/banner.jpg")
     *             ),
     *             @OA\Property(property="product_count", type="integer", description="Number of products in the subcategory", example=20),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of products in the subcategory",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                     @OA\Property(property="slug", type="string", description="Product slug", example="modern-sofa"),
     *                     @OA\Property(property="name", type="string", description="Product name", example="Modern Sofa"),
     *                     @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                     @OA\Property(property="price", type="number", format="float", description="Product price", example=300.0),
     *                     @OA\Property(property="discount", type="number", format="float", description="Discount percentage", example=15),
     *                     @OA\Property(property="discount_ends_at", type="string", format="datetime", nullable=true, description="Discount end time", example="2024-11-30T23:59:59Z"),
     *                     @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=255.0),
     *                     @OA\Property(property="main_image", type="string", description="URL of the main product image", example="http://example.com/storage/images/products/sofa.jpg"),
     *                     @OA\Property(property="average_rating", type="number", format="float", description="Average product rating", example=4.7),
     *                     @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                     @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is in the user's favorites", example=true),
     *                     @OA\Property(property="remaining_discount_seconds", type="integer", nullable=true, description="Remaining seconds for the discount", example=3600),
     *                     @OA\Property(property="badge", type="string", nullable=true, description="URL of the product badge image", example="http://example.com/storage/images/badges/badge.png"),
     *                     @OA\Property(property="badge2", type="string", nullable=true, description="URL of the secondary product badge image", example="http://example.com/storage/images/badges/badge2.png")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subcategory not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message", example="Category or Subcategory not found")
     *         )
     *     )
     * )
     */

    public function show(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'sort_by' => 'in:price,name',
            'sort_direction' => 'in:asc,desc,default',
            'per_page' => 'integer|min:1|max:100',
            'id' => 'required_without:slug|integer',
            'slug' => 'required_without:id|string'
        ]);

        // Fetch the subcategory by ID or slug
        $subcategory = Subcategory::with('category')
            ->where(function ($query) use ($request) {
                if ($request->filled('id')) {
                    $query->where('id', $request->id);
                } elseif ($request->filled('slug')) {
                    $query->where('slug', $request->slug);
                }
            })
            ->first();

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
            'name' => $subcategory->name ?? "",
            'slug' => $subcategory->slug, // Include slug here
            'description' => $subcategory->description ?? "",
            'meta_title' => $subcategory->meta_title ?? "",
            'meta_description' => $subcategory->meta_description ?? "",
            'description_web' => $subcategory->description_web ?? "",
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
            return [
                'id' => $product->id,
                'slug' => $product->slug,
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
                'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
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

    /**
     * @OA\Get(
     *     path="/api/homescreen-subcategories",
     *     operationId="getHomescreenSubcategories",
     *     tags={"Subcategories"},
     *     summary="Retrieve subcategories for the home screen widget",
     *     description="Fetches subcategories marked as homescreen widgets with their details and images.",
     *     @OA\Response(
     *         response=200,
     *         description="Homescreen subcategories retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Subcategory ID", example=1),
     *                 @OA\Property(property="name", type="string", description="Subcategory name", example="Living Room Sofas"),
     *                 @OA\Property(property="slug", type="string", description="Subcategory slug", example="living-room-sofas"),
     *                 @OA\Property(property="description", type="string", description="Subcategory description", example="Premium sofas for your living room."),
     *                 @OA\Property(property="image", type="string", description="URL of the subcategory image", example="http://example.com/storage/images/subcategories/small/sofa.jpg"),
     *                 @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/subcategories/banner/banner.jpg"),
     *                 @OA\Property(property="widget_view_image", type="string", description="URL of the widget view image", example="http://example.com/storage/images/subcategories/homescreen/widget.jpg"),
     *                 @OA\Property(property="is_popular", type="boolean", description="Indicates if the subcategory is popular", example=true)
     *             )
     *         )
     *     )
     * )
     */
    public function get_homescreen_subcategories()
    {
        $homescreenSubcategories = Subcategory::where('homescreen_widget', true)->get();

        $transformedHomescreenSubcategories = $homescreenSubcategories->map(function ($subcategory) {
            $subcategory->image = url('storage/images/subcategories/small/' . $subcategory->image);
            $subcategory->banner_image = url('storage/images/subcategories/banner/' . $subcategory->banner_image);
            $subcategory->widget_view_image = url('storage/images/subcategories/homescreen/' . $subcategory->widget_view_image);
            return [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'slug' => $subcategory->slug, // Include slug here
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
