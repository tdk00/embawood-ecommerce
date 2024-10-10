<?php
namespace App\Http\Controllers\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class PurchasedTogetherProductsController extends Controller
{
    // Display the list of related products
    public function index($productId)
    {
        $product = Product::with('purchasedTogetherProducts')->findOrFail($productId);
        return view('admin.pages.purchased_together_products.index', compact('product'));
    }

    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        $products = Product::whereNull('parent_id')->get();
        return view('admin.pages.purchased_together_products.create', compact('products', 'product'));
    }



    // Attach a related product
    public function attach(Request $request, $productId)
    {
        $purchasedTogetherProductId = $request->input('purchased_together_product_id');

        // Validate that the related product exists
        $purchasedTogetherProduct = Product::findOrFail($purchasedTogetherProductId);



        $product = Product::findOrFail($productId);

        // Check if the product is already related
        if ($product->purchasedTogetherProducts()->where('purchased_together_product_id', $purchasedTogetherProductId)->exists()) {
            return redirect()->route('admin.purchased-together-products.index', $productId)
                ->with('error', 'Tapılmadı');
        }

        // Attach the related product
        $product->purchasedTogetherProducts()->attach($purchasedTogetherProductId);

        return redirect()->route('admin.purchased-together-products.index', $productId)
            ->with('success', 'Product relation created successfully.');
    }

    // Remove a related product
    public function detach( $productId, $purchasedTogetherProductId )
    {
        $product = Product::findOrFail($productId);

        // Check if the product is related before detaching
        if (!$product->purchasedTogetherProducts()->where('purchased_together_product_id', $purchasedTogetherProductId)->exists()) {
            return redirect()->route('admin.purchased-together-products.index', $productId)
                ->with('error', 'Tapılmadı');
        }

        // Detach the related product
        $product->purchasedTogetherProducts()->detach($purchasedTogetherProductId);

        return redirect()->route('admin.purchased-together-products.index', $productId)
            ->with('success', 'Product relation deleted successfully.');
    }
}
