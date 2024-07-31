<?php

namespace App\Http\Controllers\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\SelectedProduct;
use Illuminate\Http\Request;

class ApiSelectedProductController extends Controller
{
    /**
     * Display a listing of the selected products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $selectedProducts = SelectedProduct::with('product')->orderBy('order')->get();
        return response()->json($selectedProducts);
    }

    /**
     * Display the specified selected product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $selectedProduct = SelectedProduct::with('product')->findOrFail($id);
        return response()->json($selectedProduct);
    }
}
