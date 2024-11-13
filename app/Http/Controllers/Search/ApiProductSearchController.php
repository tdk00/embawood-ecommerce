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
     *     operationId="autocompleteProduct",
     *     tags={"Search"},
     *     summary="Autocomplete product search",
     *     description="Searches for products by name and returns a list of matching products. The search is limited to the main products only.",
     *     @OA\Parameter(
     *         name="term",
     *         in="query",
     *         description="The search term used to find matching products",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Laptop"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of matching products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laptop Pro 15"),
     *                 @OA\Property(property="price", type="number", format="float", example=999.99),
     *                 @OA\Property(property="discount", type="number", format="float", example=10.5),
     *                 @OA\Property(property="discount_ends_at", type="string", format="date-time", example="2024-12-31T23:59:59Z"),
     *                 @OA\Property(property="is_set", type="boolean", example=false),
     *                 @OA\Property(property="final_price", type="number", format="float", example=899.49)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No products found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No products found")
     *         )
     *     )
     * )
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('term', '');

        // Fetch the top 5 matching products and get the total count in a single query
        $productsQuery = Product::searchByName($term);

        // Get the limited results for display
        $products = $productsQuery->take(5)->get(['id', 'name']);

        // Get the total count without running an additional query
        $totalCount = $productsQuery->count();

        // Transform the results to match the expected Flutter structure
        $transformedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            ];
        });

        return response()->json([
            'count' => $totalCount, // Total count of all matching products
            'items' => $transformedProducts->toArray(), // Top 5 matching items
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/search/search-results",
     *     operationId="searchProductResults",
     *     tags={"Search"},
     *     summary="Search for products",
     *     description="Searches for products by name and returns a list of matching products.",
     *     @OA\Parameter(
     *         name="term",
     *         in="query",
     *         description="The search term used to find matching products",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Laptop"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of matching products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laptop Pro 15"),
     *                 @OA\Property(property="price", type="number", format="float", example=999.99),
     *                 @OA\Property(property="discount", type="number", format="float", example=10.5),
     *                 @OA\Property(property="discount_ends_at", type="string", format="date-time", example="2024-12-31T23:59:59Z"),
     *                 @OA\Property(property="is_set", type="boolean", example=false),
     *                 @OA\Property(property="final_price", type="number", format="float", example=899.49)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No products found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No products found")
     *         )
     *     )
     * )
     */
    public function searchResults(Request $request)
    {
        $term = $request->get('term', '');

        $products = Product::main() // Use the 'main' scope to filter products with a null parent_id
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
                'has_limited_discount' => $product->has_limited_discount
            ];
        });

        return response()->json([
            'total_results' => $products->count(),
            'products' => $transformedProducts
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/search/search-tags",
     *     operationId="getSearchTags",
     *     tags={"Search"},
     *     summary="Get search tags",
     *     description="Retrieves a list of search tags with an optional limit on the number of tags returned.",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of search tags returned",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=3
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of search tags",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Armada Masa"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example=null),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No search tags found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No search tags found")
     *         )
     *     )
     * )
     */
    public function getSearchTags(Request $request)
    {
        $limit = $request->input('limit', 3);
        $tags = ProductSearchTag::limit($limit)->get();
        return response()->json($tags);
    }
}
