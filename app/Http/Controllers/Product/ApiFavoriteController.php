<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Favorite;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiFavoriteController extends Controller
{
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
                'badge' => url('storage/images/badge/' . $product->badge)
            ];
            return $productData;
        });

        return response()->json($transformedFavorites);
    }

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
            'favorites' => $favorites, // Return all favorites
            'message' => $message
        ]);
    }

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
                'badge' => url('storage/images/badge/' . $product->badge)
            ];

            return $productData;
        });

        return response()->json($transformedFavorites);
    }
}
