<?php

namespace App\Http\Controllers\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\MostViewedProduct;
use Illuminate\Http\Request;

class ApiMostViewedProductController extends Controller
{
    /**
     * Display a listing of the most viewed products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $mostViewedProducts = MostViewedProduct::with('product')->orderBy('order')->get();
        return response()->json($mostViewedProducts);
    }

    /**
     * Display the specified most viewed product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $mostViewedProduct = MostViewedProduct::with('product')->findOrFail($id);
        return response()->json($mostViewedProduct);
    }
}
