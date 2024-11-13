<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $images = ProductImage::where('product_id', $productId)->orderBy('order')->get();

        return view('admin.pages.products.images.index', compact('product', 'images'));
    }

    public function create($productId)
    {
        $product = Product::findOrFail($productId);

        return view('admin.pages.products.images.create', compact('product'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'order' => 'integer',
            'alt' => 'array' // Optional alt translations
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/images/products/', $filename);

        $productImage = ProductImage::create([
            'product_id' => $productId,
            'image_path' => $filename,
            'is_main' => false,
            'order' => $request->get('order', 0)
        ]);

        // Save translations only if provided
        foreach ($request->get('alt', []) as $locale => $altText) {
            if (!empty($altText)) {
                $productImage->translations()->create([
                    'locale' => $locale,
                    'alt_text' => $altText,
                ]);
            }
        }

        return redirect()->route('admin.products.images.index', $productId)
            ->with('message', 'Şəkil uğurla əlavə edildi.');
    }

    public function edit($productId, ProductImage $productImage)
    {
        $product = Product::findOrFail($productId);

        return view('admin.pages.products.images.edit', compact('product', 'productImage'));
    }

    public function update(Request $request, $productId, ProductImage $productImage)
    {
        $request->validate([
            'order' => 'integer',
            'alt' => 'array' // Optional alt translations
        ]);

        $productImage->update([
            'order' => $request->get('order', $productImage->order)
        ]);

        // Update translations only if provided
        foreach ($request->get('alt', []) as $locale => $altText) {
            if (!empty($altText)) {
                $productImage->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['alt_text' => $altText]
                );
            }
        }

        return redirect()->route('admin.products.images.index', $productId)
            ->with('message', 'Şəkil uğurla yeniləndi.');
    }

    public function destroy($productId, ProductImage $productImage)
    {
        Storage::delete('public/images/products/' . $productImage->image_path);
        $productImage->delete();

        return redirect()->route('admin.products.images.index', $productId)
            ->with('message', 'Şəkil uğurla silindi.');
    }
}
