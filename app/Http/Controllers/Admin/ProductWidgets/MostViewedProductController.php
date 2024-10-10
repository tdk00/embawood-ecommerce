<?php

namespace App\Http\Controllers\Admin\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\MostViewedProduct;
use App\Models\ProductWidgets\NewProduct;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class MostViewedProductController extends Controller
{
    /**
     * Display a listing of the most viewed products.
     */
    public function index()
    {
        $mostViewedProducts = MostViewedProduct::whereHas('product', function ($query) {
            $query->main();
        })->orderBy('order')->get();
        return view('admin.pages.most_viewed_products.index', compact('mostViewedProducts'));
    }

    /**
     * Show the form for creating a new most viewed product widget.
     */
    public function create()
    {
        $products = Product::main()->get(); // Fetch all products for selection
        return view('admin.pages.most_viewed_products.create', compact('products'));
    }

    /**
     * Store a newly created most viewed product widget in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order' => 'required|integer',
        ]);

        // Check if the product is already attached
        $existingProduct = MostViewedProduct::where('product_id', $request->product_id)->first();

        if ($existingProduct) {
            return redirect()->back()->withErrors(['product_id' => 'This product is already attached as a most viewed product.']);
        }

        MostViewedProduct::create($request->only('product_id', 'order'));

        return redirect()->route('admin.most-viewed-products.index')->with('success', 'Most viewed product widget created successfully.');
    }

    /**
     * Show the form for editing the specified most viewed product widget.
     */
    public function edit(MostViewedProduct $mostViewedProduct)
    {
        $products = Product::main()->get(); // Fetch all products for selection
        return view('admin.pages.most_viewed_products.edit', compact('mostViewedProduct', 'products'));
    }

    /**
     * Update the specified most viewed product widget in storage.
     */
    public function update(Request $request, MostViewedProduct $mostViewedProduct)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order' => 'required|integer',
        ]);

        // Check if the product is already attached to another MostViewedProduct
        $existingProduct = MostViewedProduct::where('product_id', $request->product_id)
            ->where('id', '!=', $mostViewedProduct->id)
            ->first();

        if ($existingProduct) {
            return redirect()->back()->withErrors(['product_id' => 'This product is already attached as a most viewed product.']);
        }

        $mostViewedProduct->update($request->only('product_id', 'order'));

        return redirect()->route('admin.most-viewed-products.index')->with('success', 'Most viewed product widget updated successfully.');
    }

    /**
     * Remove the specified most viewed product widget from storage.
     */
    public function destroy(MostViewedProduct $mostViewedProduct)
    {
        $mostViewedProduct->delete();

        return redirect()->route('admin.most-viewed-products.index')->with('success', 'Most viewed product widget deleted successfully.');
    }
}
