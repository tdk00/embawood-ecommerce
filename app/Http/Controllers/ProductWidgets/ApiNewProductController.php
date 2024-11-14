<?php

namespace App\Http\Controllers\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\NewProduct;
use Illuminate\Http\Request;

class ApiNewProductController extends Controller
{
    /**
     * Display a listing of the new products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 'all');

        // Validate that the limit is a positive integer or the keyword 'all'
        if ($limit !== 'all' && (!is_numeric($limit) || (int)$limit <= 0)) {
            return response()->json(['error' => 'Limit must be a positive integer or "all"'], 400);
        }

        $query = NewProduct::with('product.images')->orderBy('order', 'asc');

        // Apply the limit if it's a valid number
        if ($limit !== 'all') {
            $limit = (int)$limit;
            $query->limit($limit);
        }

        $newProducts = $query->get();

        $transformedNewProducts = $newProducts->map(function ($newProduct) {
            $product = $newProduct->product;

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

        return response()->json($transformedNewProducts);
    }

    /**
     * Display the specified new product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $newProduct = NewProduct::with('product')->findOrFail($id);
        return response()->json($newProduct);
    }
}
