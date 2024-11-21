<?php

namespace App\Http\Controllers\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\SelectedProduct;
use Illuminate\Http\Request;

class ApiSelectedProductController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/selected-products",
     *     operationId="getSelectedProducts",
     *     tags={"Selected Products"},
     *     summary="Retrieve a list of selected products",
     *     description="Fetches a list of selected products with optional limit. Includes product details such as price, discount, and images.",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Maximum number of selected products to retrieve, or 'all' for no limit",
     *         @OA\Schema(type="string", example="10", enum={"all", "10", "20", "50"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of selected products retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                 @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                 @OA\Property(property="slug", type="string", description="Product slug", example="wooden-chair"),
     *                 @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                 @OA\Property(property="price", type="number", format="float", description="Product price", example=100.0),
     *                 @OA\Property(property="discount", type="number", format="float", description="Discount percentage", example=10),
     *                 @OA\Property(property="discount_ends_at", type="string", format="datetime", nullable=true, description="Discount expiration time", example="2024-11-30T23:59:59Z"),
     *                 @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=90.0),
     *                 @OA\Property(property="main_image", type="string", description="URL of the main product image", example="http://example.com/storage/images/products/image.jpg"),
     *                 @OA\Property(property="average_rating", type="number", format="float", description="Average customer rating", example=4.5),
     *                 @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                 @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is in the user's favorites", example=false),
     *                 @OA\Property(property="remaining_discount_seconds", type="integer", nullable=true, description="Remaining seconds for the discount", example=3600),
     *                 @OA\Property(property="has_unlimited_discount", type="boolean", description="Indicates if the discount is unlimited", example=false),
     *                 @OA\Property(property="has_limited_discount", type="boolean", description="Indicates if the discount is limited", example=true),
     *                 @OA\Property(property="badge", type="string", nullable=true, description="URL of the product badge image", example="http://example.com/storage/images/badge/badge1.png"),
     *                 @OA\Property(property="badge2", type="string", nullable=true, description="URL of the secondary product badge image", example="http://example.com/storage/images/badge/badge2.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid limit parameter",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message", example="Limit must be a positive integer or 'all'")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 'all');

        // Validate that the limit is a positive integer or the keyword 'all'
        if ($limit !== 'all' && (!is_numeric($limit) || (int)$limit <= 0)) {
            return response()->json(['error' => 'Limit must be a positive integer or "all"'], 400);
        }

        $query = SelectedProduct::with('product.images')->orderBy('order', 'asc');

        // Apply the limit if it's a valid number
        if ($limit !== 'all') {
            $limit = (int)$limit;
            $query->limit($limit);
        }

        $mostViewedProducts = $query->get();

        $transformedMostViewedProducts = $mostViewedProducts->map(function ($mostViewedProduct) {
            $product = $mostViewedProduct->product;

            $product->main_image = url('storage/images/products/' . $product->main_image);
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
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
            return $productData;
        });

        return response()->json($transformedMostViewedProducts);
    }

    /**
     * @OA\Get(
     *     path="/api/selected-products/{id}",
     *     operationId="getSelectedProductDetails",
     *     tags={"Selected Products"},
     *     summary="Retrieve details of a specific selected product",
     *     description="Fetches detailed information about a specific selected product by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the selected product",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Selected product details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Selected product ID", example=1),
     *             @OA\Property(property="product", type="object", description="Associated product details",
     *                 @OA\Property(property="id", type="integer", description="Product ID", example=100),
     *                 @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                 @OA\Property(property="slug", type="string", description="Product slug", example="wooden-chair"),
     *                 @OA\Property(property="price", type="number", format="float", description="Product price", example=100.0),
     *                 @OA\Property(property="final_price", type="number", format="float", description="Final price after discounts", example=90.0),
     *                 @OA\Property(property="main_image", type="string", description="URL of the main product image", example="http://example.com/storage/images/products/image.jpg")
     *             ),
     *             @OA\Property(property="order", type="integer", description="Display order of the selected product", example=1),
     *             @OA\Property(property="created_at", type="string", format="datetime", description="Creation date", example="2024-11-20T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="datetime", description="Last update date", example="2024-11-20T12:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Selected product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $mostViewedProduct = SelectedProduct::with('product')->findOrFail($id);
        return response()->json($mostViewedProduct);
    }
}
