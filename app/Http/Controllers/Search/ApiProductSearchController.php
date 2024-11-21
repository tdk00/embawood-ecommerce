<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Search\ProductSearchTag;
use Illuminate\Http\Request;

class ApiProductSearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/search/autocomplete",
     *     operationId="autocompleteSearch",
     *     tags={"Search"},
     *     summary="Autocomplete product search",
     *     description="Provides up to 5 product suggestions based on a search term.",
     *     @OA\Parameter(
     *         name="term",
     *         in="query",
     *         required=false,
     *         description="Search term to find products",
     *         @OA\Schema(type="string", example="chair")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autocomplete suggestions retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="count", type="integer", description="Total count of matching products", example=12),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="List of product suggestions",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                     @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="Invalid request")
     *         )
     *     )
     * )
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('term', '');

        $productsQuery = Product::searchByName($term);

        $products = $productsQuery->take(5)->get(['id', 'name']);

        $totalCount = $productsQuery->count();

        $transformedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            ];
        });

        return response()->json([
            'count' => $totalCount,
            'items' => $transformedProducts->toArray(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/search/search-results",
     *     operationId="searchResults",
     *     tags={"Search"},
     *     summary="Get search results for products",
     *     description="Retrieves a list of products matching the search term.",
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="term", type="string", description="Search term to filter products", example="chair")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total_results", type="integer", description="Total number of matching products", example=5),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of matching products",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                     @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                     @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                     @OA\Property(property="price", type="number", format="float", description="Product price", example=120.99),
     *                     @OA\Property(property="discount", type="number", format="float", description="Discount amount", example=10),
     *                     @OA\Property(property="discount_ends_at", type="string", format="date-time", description="Discount end time", example="2024-12-31T23:59:59Z"),
     *                     @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=110.99),
     *                     @OA\Property(property="main_image", type="string", description="URL to the product's main image", example="https://example.com/images/products/chair.jpg"),
     *                     @OA\Property(property="average_rating", type="number", format="float", description="Average product rating", example=4.5),
     *                     @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                     @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is marked as favorite", example=true),
     *                     @OA\Property(property="remaining_discount_seconds", type="integer", description="Remaining time for the discount in seconds", example=3600),
     *                     @OA\Property(property="has_unlimited_discount", type="boolean", description="Indicates if the product has an unlimited discount", example=false),
     *                     @OA\Property(property="has_limited_discount", type="boolean", description="Indicates if the product has a limited-time discount", example=true),
     *                     @OA\Property(property="badge", type="string", description="URL to the first badge image", example="https://example.com/images/badges/new.png"),
     *                     @OA\Property(property="badge2", type="string", description="URL to the second badge image", example="https://example.com/images/badges/sale.png")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="Invalid request")
     *         )
     *     )
     * )
     */

    public function searchResults(Request $request)
    {
        $term = $request->get('term', '');

        $products = Product::main()
        ->where(function($query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%");
        })->get();

        $transformedProducts = $products->map(function ($product) {
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
                'has_limited_discount' => $product->has_limited_discount,
                'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
            ];
        });

        return response()->json([
            'total_results' => $products->count(),
            'products' => $transformedProducts
        ]);
    }
}
