<?php

namespace App\Http\Controllers\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\SelectedProduct;
use Illuminate\Http\Request;

class ApiSelectedProductController extends Controller
{

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

        return response()->json($transformedMostViewedProducts);
    }

    public function show($id)
    {
        $mostViewedProduct = SelectedProduct::with('product')->findOrFail($id);
        return response()->json($mostViewedProduct);
    }
}
