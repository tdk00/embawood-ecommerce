<?php
namespace App\Http\Controllers\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class RelatedProductsController extends Controller
{
    // Display the list of related products
    public function index($productId)
    {
        $product = Product::with('similarProducts')->findOrFail($productId);
        return view('admin.pages.related_products.index', compact('product'));
    }

    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        $products = Product::whereNull('parent_id')->get();
        return view('admin.pages.related_products.create', compact('products', 'product'));
    }



    // Attach a related product
    public function attach(Request $request, $productId)
    {
        $relatedProductId = $request->input('related_product_id');

        // Validate that the related product exists
        $relatedProduct = Product::findOrFail($relatedProductId);

        $product = Product::findOrFail($productId);

        // Check if the product is already related
        if ($product->similarProducts()->where('similar_product_id', $relatedProductId)->exists()) {
            return redirect()->route('admin.related-products.index', $productId)
                ->with('error', 'Tapılmadı');
        }

        // Attach the related product
        $product->similarProducts()->attach($relatedProductId);

        return redirect()->route('admin.related-products.index', $productId)
            ->with('success', 'Related created successfully.');
    }

    // Remove a related product
    public function detach($productId, $relatedProductId)
    {
        $product = Product::findOrFail($productId);

        // Check if the product is related before detaching
        if (!$product->similarProducts()->where('similar_product_id', $relatedProductId)->exists()) {
            return redirect()->route('admin.related-products.index', $productId)
                ->with('error', 'Tapılmadı');
        }

        // Detach the related product
        $product->similarProducts()->detach($relatedProductId);

        return redirect()->route('admin.related-products.index', $productId)
            ->with('success', 'Related deleted successfully.');
    }
}
