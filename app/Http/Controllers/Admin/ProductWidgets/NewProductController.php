<?php

namespace App\Http\Controllers\Admin\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\ProductWidgets\NewProduct;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class NewProductController extends Controller
{
    /**
     * Display a listing of the new products.
     */
    public function index()
    {
        $newProducts = NewProduct::whereHas('product', function ($query) {
            $query->main();
        })->orderBy('order')->get();
        return view('admin.pages.new_products.index', compact('newProducts'));
    }

    /**
     * Show the form for creating a new product widget.
     */
    public function create()
    {
        $products = Product::main()->get(); // Fetch all products for selection
        return view('admin.pages.new_products.create', compact('products'));
    }

    /**
     * Store a newly created new product widget in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order' => 'required|integer',
        ]);

        // Check if the product is already attached
        $existingNewProduct = NewProduct::where('product_id', $request->product_id)->first();

        if ($existingNewProduct) {
            return redirect()->back()->withErrors(['product_id' => 'This product is already attached.']);
        }

        NewProduct::create($request->only('product_id', 'order'));

        return redirect()->route('admin.new-products.index')->with('success', 'New product widget created successfully.');
    }

    /**
     * Show the form for editing the specified new product widget.
     */
    public function edit(NewProduct $newProduct)
    {
        $products = Product::main()->get(); // Fetch all products for selection
        return view('admin.pages.new_products.edit', compact('newProduct', 'products'));
    }

    /**
     * Update the specified new product widget in storage.
     */
    public function update(Request $request, NewProduct $newProduct)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order' => 'required|integer',
        ]);

        // Check if the product is already attached to another NewProduct
        $existingNewProduct = NewProduct::where('product_id', $request->product_id)
            ->where('id', '!=', $newProduct->id)
            ->first();

        if ($existingNewProduct) {
            return redirect()->back()->withErrors(['product_id' => 'This product is already attached.']);
        }

        $newProduct->update($request->only('product_id', 'order'));

        return redirect()->route('admin.new-products.index')->with('success', 'New product widget updated successfully.');
    }

    /**
     * Remove the specified new product widget from storage.
     */
    public function destroy(NewProduct $newProduct)
    {
        $newProduct->delete();

        return redirect()->route('admin.new-products.index')->with('success', 'New product widget deleted successfully.');
    }
}
