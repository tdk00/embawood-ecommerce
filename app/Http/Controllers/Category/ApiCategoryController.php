<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Ideas\Idea;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/homescreen-categories",
     *     operationId="getHomescreenCategories",
     *     tags={"Categories"},
     *     summary="Retrieve categories for the home screen widget",
     *     description="Fetches categories marked as homescreen widgets with their details and images.",
     *     @OA\Response(
     *         response=200,
     *         description="Homescreen categories retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Category ID", example=1),
     *                 @OA\Property(property="slug", type="string", description="Category slug", example="living-room"),
     *                 @OA\Property(property="name", type="string", description="Category name", example="Living Room"),
     *                 @OA\Property(property="description", type="string", description="Category description", example="Explore our living room furniture."),
     *                 @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/category/banner/banner.jpg"),
     *                 @OA\Property(property="widget_view_image", type="string", description="URL of the widget view image", example="http://example.com/storage/images/category/widget_images/widget.jpg")
     *             )
     *         )
     *     )
     * )
     */

    public function get_homescreen_categories()
    {
        $homescreenCategories = Category::where('homescreen_widget', true)->orderBy('order')->get();

        $transformedHomescreenCategories = $homescreenCategories->map(function ($category) {
            $category->banner_image = url('storage/images/category/banner/' . $category->banner_image);
            $category->widget_view_image = url('storage/images/category/widget_images/' . $category->widget_view_image);
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
                'description' => $category->description,
                'banner_image' => $category->banner_image,
                'widget_view_image' => $category->widget_view_image,
            ];
        });

        return response()->json($transformedHomescreenCategories);
    }
    /**
     * @OA\Get(
     *     path="/api/categories-with-subcategories",
     *     operationId="getCategoriesWithSubcategories",
     *     tags={"Categories"},
     *     summary="Retrieve all categories with their subcategories",
     *     description="Fetches all categories along with their subcategories and associated images.",
     *     @OA\Response(
     *         response=200,
     *         description="Categories with subcategories retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", description="Response status", example="success"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Category ID", example=1),
     *                     @OA\Property(property="slug", type="string", description="Category slug", example="living-room"),
     *                     @OA\Property(property="name", type="string", description="Category name", example="Living Room"),
     *                     @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/category/banner/banner.jpg"),
     *                     @OA\Property(
     *                         property="subcategories",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", description="Subcategory ID", example=10),
     *                             @OA\Property(property="slug", type="string", description="Subcategory slug", example="sofas"),
     *                             @OA\Property(property="name", type="string", description="Subcategory name", example="Sofas"),
     *                             @OA\Property(property="image", type="string", description="URL of the subcategory image", example="http://example.com/storage/images/subcategories/small/sofas.jpg")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="ideas",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Idea ID", example=100),
     *                     @OA\Property(property="name", type="string", description="Idea title", example="Cozy Living Room Ideas"),
     *                     @OA\Property(
     *                         property="subideas",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", description="Subidea ID", example=101),
     *                             @OA\Property(property="name", type="string", description="Subidea title", example="Modern Sofa Arrangement"),
     *                             @OA\Property(property="image", type="string", description="URL of the subidea image", example="http://example.com/storage/images/ideas/subidea.jpg")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function getCategoriesWithSubcategory()
    {
        $categories = Category::with(['subcategories' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('order')->get();
        $ideas = Idea::with('subideas')->get();

        $transformedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
                'banner_image' => url('storage/images/category/banner/' . $category->banner_image),
                'subcategories' => $category->subcategories->map(function ($subcategory) {
                    $subcategory->image = url('storage/images/subcategories/small/' . $subcategory->image);
                    return [
                        'id' => $subcategory->id,
                        'slug' => $subcategory->slug,
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

    /**
     * @OA\Get(
     *     path="/api/category-details/{id}",
     *     operationId="getCategoryDetails",
     *     tags={"Categories"},
     *     summary="Retrieve details of a specific category by ID",
     *     description="Fetches category details along with its subcategories and products.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="slug", type="string", description="Category slug", example="living-room"),
     *             @OA\Property(property="name", type="string", description="Category name", example="Living Room"),
     *             @OA\Property(property="description", type="string", description="Category description", example="Explore our living room furniture."),
     *             @OA\Property(property="meta_title", type="string", description="Meta title for SEO", example="Living Room Furniture"),
     *             @OA\Property(property="meta_description", type="string", description="Meta description for SEO", example="Discover premium furniture for your living room."),
     *             @OA\Property(property="description_web", type="string", description="Detailed web description", example="A wide selection of living room furniture for all tastes."),
     *             @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/category/banner/banner.jpg"),
     *             @OA\Property(property="product_count", type="integer", description="Total number of products in the category", example=50),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of products in the category",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                     @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                     @OA\Property(property="slug", type="string", description="Product slug", example="wooden-chair"),
     *                     @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                     @OA\Property(property="price", type="number", format="float", description="Product price", example=100.0),
     *                     @OA\Property(property="discount", type="number", format="float", description="Discount percentage", example=10),
     *                     @OA\Property(property="discount_ends_at", type="string", format="datetime", nullable=true, description="Discount expiration time", example="2024-11-30T23:59:59Z"),
     *                     @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=90.0),
     *                     @OA\Property(property="main_image", type="string", description="URL of the main product image", example="http://example.com/storage/images/products/image.jpg"),
     *                     @OA\Property(property="average_rating", type="number", format="float", description="Average customer rating", example=4.5),
     *                     @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                     @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is in the user's favorites", example=false),
     *                     @OA\Property(property="remaining_discount_seconds", type="integer", nullable=true, description="Remaining seconds for the discount", example=3600),
     *                     @OA\Property(property="has_unlimited_discount", type="boolean", description="Indicates if the discount is unlimited", example=false),
     *                     @OA\Property(property="has_limited_discount", type="boolean", description="Indicates if the discount is limited", example=true),
     *                     @OA\Property(property="badge", type="string", nullable=true, description="URL of the product badge image", example="http://example.com/storage/images/badge/badge1.png"),
     *                     @OA\Property(property="badge2", type="string", nullable=true, description="URL of the secondary product badge image", example="http://example.com/storage/images/badge/badge2.png")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="subcategories",
     *                 type="array",
     *                 description="List of subcategories",
     *                 @OA\Items(
     *                     @OA\Property(property="category_id", type="integer", description="Subcategory ID", example=10),
     *                     @OA\Property(property="name", type="string", description="Subcategory name", example="Sofas"),
     *                     @OA\Property(property="description", type="string", description="Subcategory description", example="Comfortable sofas for your living room."),
     *                     @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/subcategories/banner/banner.jpg"),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         description="Products in the subcategory",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", description="Product ID", example=101),
     *                             @OA\Property(property="name", type="string", description="Product name", example="Modern Sofa"),
     *                             @OA\Property(property="price", type="number", format="float", description="Product price", example=200.0)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Category not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::with([
            'subcategories' => function ($query) {
                $query->orderBy('order'); // Order subcategories by 'order'
            },
            'subcategories.products' // Load products without ordering
        ])->find($id);

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
                    'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                    'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
                ];
            });
        });

        return response()->json([
            'slug' => $category->slug,
            'name' => $category->name,
            'description' => $category->description,
            'meta_title' => $category->meta_title,
            'meta_description' => $category->meta_description,
            'description_web' => $category->description_web,
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
                            'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                            'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/category/{slug}",
     *     operationId="getCategoryDetailsBySlug",
     *     tags={"Categories"},
     *     summary="Retrieve details of a specific category by slug",
     *     description="Fetches category details along with its subcategories and products.",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Category slug",
     *         @OA\Schema(type="string", example="qonaq")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="slug", type="string", description="Category slug", example="living-room"),
     *             @OA\Property(property="name", type="string", description="Category name", example="Living Room"),
     *             @OA\Property(property="description", type="string", description="Category description", example="Explore our living room furniture."),
     *             @OA\Property(property="meta_title", type="string", description="Meta title for SEO", example="Living Room Furniture"),
     *             @OA\Property(property="meta_description", type="string", description="Meta description for SEO", example="Discover premium furniture for your living room."),
     *             @OA\Property(property="description_web", type="string", description="Detailed web description", example="A wide selection of living room furniture for all tastes."),
     *             @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/category/banner/banner.jpg"),
     *             @OA\Property(property="product_count", type="integer", description="Total number of products in the category", example=50),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of products in the category",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                     @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                     @OA\Property(property="slug", type="string", description="Product slug", example="wooden-chair"),
     *                     @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                     @OA\Property(property="price", type="number", format="float", description="Product price", example=100.0),
     *                     @OA\Property(property="discount", type="number", format="float", description="Discount percentage", example=10),
     *                     @OA\Property(property="discount_ends_at", type="string", format="datetime", nullable=true, description="Discount expiration time", example="2024-11-30T23:59:59Z"),
     *                     @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=90.0),
     *                     @OA\Property(property="main_image", type="string", description="URL of the main product image", example="http://example.com/storage/images/products/image.jpg"),
     *                     @OA\Property(property="average_rating", type="number", format="float", description="Average customer rating", example=4.5),
     *                     @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                     @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is in the user's favorites", example=false),
     *                     @OA\Property(property="remaining_discount_seconds", type="integer", nullable=true, description="Remaining seconds for the discount", example=3600),
     *                     @OA\Property(property="has_unlimited_discount", type="boolean", description="Indicates if the discount is unlimited", example=false),
     *                     @OA\Property(property="has_limited_discount", type="boolean", description="Indicates if the discount is limited", example=true),
     *                     @OA\Property(property="badge", type="string", nullable=true, description="URL of the product badge image", example="http://example.com/storage/images/badge/badge1.png"),
     *                     @OA\Property(property="badge2", type="string", nullable=true, description="URL of the secondary product badge image", example="http://example.com/storage/images/badge/badge2.png")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="subcategories",
     *                 type="array",
     *                 description="List of subcategories",
     *                 @OA\Items(
     *                     @OA\Property(property="category_id", type="integer", description="Subcategory ID", example=10),
     *                     @OA\Property(property="name", type="string", description="Subcategory name", example="Sofas"),
     *                     @OA\Property(property="description", type="string", description="Subcategory description", example="Comfortable sofas for your living room."),
     *                     @OA\Property(property="banner_image", type="string", description="URL of the banner image", example="http://example.com/storage/images/subcategories/banner/banner.jpg"),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         description="Products in the subcategory",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", description="Product ID", example=101),
     *                             @OA\Property(property="name", type="string", description="Product name", example="Modern Sofa"),
     *                             @OA\Property(property="price", type="number", format="float", description="Product price", example=200.0)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Category not found")
     *         )
     *     )
     * )
     */
    public function showBySlug($slug)
    {
        $category = Category::with([
            'subcategories' => function ($query) {
                $query->orderBy('order'); // Order subcategories by 'order'
            },
            'subcategories.products' // Load products without ordering
        ])->where('slug', $slug)->first();

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
                    'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                    'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
                ];
            });
        });

        return response()->json([
            'slug' => $category->slug, // Add slug here for the category
            'name' => $category->name,
            'description' => $category->description,
            'meta_title' => $category->meta_title,
            'meta_description' => $category->meta_description,
            'description_web' => $category->description_web,
            'banner_image' => url('storage/images/category/banner/' . $category->banner_image),
            'product_count' => $allProducts->count(),
            'products' => $allProducts,
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
                            'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                            'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
