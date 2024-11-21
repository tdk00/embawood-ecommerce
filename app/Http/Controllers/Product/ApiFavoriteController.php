<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Favorite;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiFavoriteController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     operationId="getUserFavorites",
     *     tags={"Favorites"},
     *     summary="Retrieve all favorite products for the authenticated user",
     *     description="Returns a list of all favorite products for the authenticated user with product details.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of favorite products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                 @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                 @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                 @OA\Property(property="price", type="number", format="float", description="Price of the product", example=100.0),
     *                 @OA\Property(property="discount", type="number", format="float", description="Discount amount", example=10.0),
     *                 @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=90.0),
     *                 @OA\Property(property="main_image", type="string", description="URL of the product's main image", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="average_rating", type="number", format="float", description="Average rating of the product", example=4.5),
     *                 @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=true),
     *                 @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is a favorite", example=true),
     *                 @OA\Property(property="remaining_discount_seconds", type="integer", description="Time remaining for the discount (in seconds)", example=3600),
     *                 @OA\Property(property="has_unlimited_discount", type="boolean", description="Indicates if the discount is unlimited", example=false),
     *                 @OA\Property(property="badge", type="string", nullable=true, description="URL of the first badge image", example="https://example.com/badge1.png"),
     *                 @OA\Property(property="badge2", type="string", nullable=true, description="URL of the second badge image", example="https://example.com/badge2.png")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $userId = Auth::id();
        $favorites = Favorite::where('user_id', $userId)->with('product')->get();

        $transformedFavorites = $favorites->map(function ($favorite) {
            $product = $favorite->product;

            $product->main_image = url('storage/images/products/' . $product->main_image);
            $productData = [
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
            return $productData;
        });

        return response()->json($transformedFavorites);
    }

    /**
     * @OA\Post(
     *     path="/api/favorites/toggle",
     *     operationId="toggleFavorite",
     *     tags={"Favorites"},
     *     summary="Add or remove a product from favorites",
     *     description="Toggles the favorite status of a product for the authenticated user. Returns the updated favorite status and all favorite product IDs.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", description="ID of the product to toggle", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorite status toggled",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(property="product_id", type="integer", description="ID of the product", example=1),
     *             @OA\Property(property="product_name", type="string", description="Name of the product", example="Wooden Chair"),
     *             @OA\Property(property="is_favorite", type="boolean", description="Updated favorite status", example=true),
     *             @OA\Property(
     *                 property="favorites",
     *                 type="array",
     *                 description="List of all favorite product IDs",
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *             @OA\Property(property="message", type="string", description="Operation message", example="Product added to favorites")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="error"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found")
     *         )
     *     )
     * )
     */
    public function toggle(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404); // Return a 404 not found response
        }

        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Product removed from favorites';
            $isFavorite = false;
        } else {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $message = 'Product added to favorites';
            $isFavorite = true;
        }

        // Fetch all favorites for the user
        $favorites = Favorite::where('user_id', $userId)->pluck('product_id')->toArray();

        return response()->json([
            'status' => 'success',
            'product_id' => $productId,
            'product_name' => $product->name,
            'is_favorite' => $isFavorite,
            'favorites' => $favorites,
            'message' => $message
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/favorites/remove",
     *     operationId="removeFavorite",
     *     tags={"Favorites"},
     *     summary="Remove a product from favorites",
     *     description="Removes a product from the authenticated user's favorites. Returns the updated list of favorite products.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", description="ID of the product to remove from favorites", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from favorites",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Product ID", example=1),
     *                 @OA\Property(property="name", type="string", description="Product name", example="Wooden Chair"),
     *                 @OA\Property(property="is_set", type="boolean", description="Indicates if the product is part of a set", example=false),
     *                 @OA\Property(property="price", type="number", format="float", description="Price of the product", example=100.0),
     *                 @OA\Property(property="discount", type="number", format="float", description="Discount amount", example=10.0),
     *                 @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=90.0),
     *                 @OA\Property(property="main_image", type="string", description="URL of the product's main image", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="average_rating", type="number", format="float", description="Average rating of the product", example=4.5),
     *                 @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=true),
     *                 @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is a favorite", example=true),
     *                 @OA\Property(property="remaining_discount_seconds", type="integer", description="Time remaining for the discount (in seconds)", example=3600),
     *                 @OA\Property(property="has_unlimited_discount", type="boolean", description="Indicates if the discount is unlimited", example=false),
     *                 @OA\Property(property="badge", type="string", nullable=true, description="URL of the first badge image", example="https://example.com/badge1.png"),
     *                 @OA\Property(property="badge2", type="string", nullable=true, description="URL of the second badge image", example="https://example.com/badge2.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found or not in favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="error"),
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found")
     *         )
     *     )
     * )
     */
    public function remove(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        // Check if the product exists
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404); // Return a 404 not found response
        }

        // Find the favorite entry for the user and product
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            // Delete the favorite if it exists
            $favorite->delete();
            $message = 'Product removed from favorites';
        } else {
            // Return a message if the product was not in favorites
            return response()->json([
                'status' => 'error',
                'message' => 'Product is not in favorites',
            ], 404);
        }

        // Fetch all remaining favorites for the user after removal
        $favorites = Favorite::where('user_id', $userId)->with('product')->get();

        // Transform the favorites data
        $transformedFavorites = $favorites->map(function ($favorite) {
            $product = $favorite->product;

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
                'is_favorite' => true, // Always true for favorites
                'remaining_discount_seconds' => $product->remaining_discount_seconds,
                'has_unlimited_discount' => $product->has_unlimited_discount,
                'has_limited_discount' => $product->has_limited_discount,
                'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
            ];

            return $productData;
        });

        return response()->json($transformedFavorites);
    }
}
