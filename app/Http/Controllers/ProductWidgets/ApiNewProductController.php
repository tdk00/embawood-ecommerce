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

        return response()->json($newProducts);
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
