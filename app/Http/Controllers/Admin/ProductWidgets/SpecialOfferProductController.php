<?php

namespace App\Http\Controllers\Admin\ProductWidgets;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\ProductWidgets\SpecialOfferProduct;
use Illuminate\Http\Request;

class SpecialOfferProductController extends Controller
{
    /**
     * Display a listing of the special offer products.
     */
    public function index()
    {
        $specialOfferProducts = SpecialOfferProduct::whereHas('product', function ($query) {
            $query->main();
        })->orderBy('order')->get();

        return view('admin.pages.special_offer_products.index', compact('specialOfferProducts'));
    }

    /**
     * Show the form for creating a new special offer product widget.
     */
    public function create()
    {
        $products = Product::main()->get(); // Fetch all products for selection
        return view('admin.pages.special_offer_products.create', compact('products'));
    }

    /**
     * Store a newly created special offer product widget in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order' => 'required|integer',
        ]);

        // Check if the product is already attached
        $existingProduct = SpecialOfferProduct::where('product_id', $request->product_id)->first();

        if ($existingProduct) {
            return redirect()->back()->withErrors(['product_id' => 'This product is already attached as a special offer product.']);
        }

        SpecialOfferProduct::create($request->only('product_id', 'order'));

        return redirect()->route('admin.special-offer-products.index')->with('success', 'Special offer product widget created successfully.');
    }

    /**
     * Show the form for editing the specified special offer product widget.
     */
    public function edit(SpecialOfferProduct $specialOfferProduct)
    {
        $products = Product::main()->get(); // Fetch all products for selection
        return view('admin.pages.special_offer_products.edit', compact('specialOfferProduct', 'products'));
    }

    /**
     * Update the specified special offer product widget in storage.
     */
    public function update(Request $request, SpecialOfferProduct $specialOfferProduct)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order' => 'required|integer',
        ]);

        // Check if the product is already attached to another SpecialOfferProduct
        $existingProduct = SpecialOfferProduct::where('product_id', $request->product_id)
            ->where('id', '!=', $specialOfferProduct->id)
            ->first();

        if ($existingProduct) {
            return redirect()->back()->withErrors(['product_id' => 'This product is already attached as a special offer product.']);
        }

        $specialOfferProduct->update($request->only('product_id', 'order'));

        return redirect()->route('admin.special-offer-products.index')->with('success', 'Special offer product widget updated successfully.');
    }

    /**
     * Remove the specified special offer product widget from storage.
     */
    public function destroy(SpecialOfferProduct $specialOfferProduct)
    {
        $specialOfferProduct->delete();

        return redirect()->route('admin.special-offer-products.index')->with('success', 'Special offer product widget deleted successfully.');
    }
}
