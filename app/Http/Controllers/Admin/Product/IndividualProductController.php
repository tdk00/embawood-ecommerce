<?php
namespace App\Http\Controllers\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use App\Models\Category\Subcategory;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndividualProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        // You can add filtering and pagination here
        $products = Product::where('is_set', false)
            ->get(); // Adjust per page limit as needed

        return view('admin.pages.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $subcategories = Subcategory::all();
        return view('admin.pages.products.create', compact('subcategories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Custom validation messages in Azerbaijani
        $messages = [
            'name_az.required' => 'Məhsul adı (AZ) tələb olunur.',
            'name_en.required' => 'Product name (EN) is required.',
            'name_ru.required' => 'Название продукта (RU) требуется.',
            'price.required' => 'Məhsulun qiyməti tələb olunur.',
            'price.numeric' => 'Qiymət düzgün formatda olmalıdır.',
            'discount.numeric' => 'Endirim düzgün rəqəm olmalıdır.',
            'discount.max' => 'Endirim 100%-dən çox ola bilməz.',
            'discount_ends_at.after' => 'Endirim müddəti bugündən sonrakı tarixdə olmalıdır.',
        ];

        // Validation
        $validated = $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'color' => 'nullable|string',
            'sku' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_ends_at' => 'nullable|date|after:today',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'main_image' => 'required|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'selected_sub_category_id' => 'exists:subcategories,id',
        ], $messages);

        try {
            // Create the base product
            $product = Product::create([
                'name' => $request->name_az,
                'description' => $request->description_az,
                'sku' => $request->sku,
                'color' => $request->color ?? null,
                'price' => $request->price,
                'stock' => $request->stock,
                'discount' => $request->discount ?? null,
                'discount_ends_at' => $request->discount_ends_at ?? null,
            ]);

            $product->subcategories()->attach($validated['selected_sub_category_id']);

            // Handling the main image
            $product->translations()->createMany([
                [
                    'locale' => 'az',
                    'name' => $request->name_az,
                    'description' => $request->description_az,
                ],
                [
                    'locale' => 'en',
                    'name' => $request->name_en,
                    'description' => $request->description_en,
                ],
                [
                    'locale' => 'ru',
                    'name' => $request->name_ru,
                    'description' => $request->description_ru,
                ],
            ]);

            // Handle the main image
            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/products/', $filename);

                ProductImage::create([
                    'is_main' => 1,
                    'product_id' => $product->id,
                    'image_path' => $filename,
                ]);
            }

            // Handle multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = $image->store('images/products', 'public');
                    $filenameOnly = basename($filename);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $filenameOnly,
                    ]);
                }
            }

            return response()->json(['message' => 'Məhsul uğurla yaradıldı!']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Məhsulun yaradılmasında xəta baş verdi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // You can show more details or statistics about the product here
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit( $id )
    {
        $product = Product::with('images', 'colorVariations')->findOrFail($id);
        $subcategories = Subcategory::all();
        $existingFiles = $product->images()
            ->where('is_main', '!=', 1)
            ->get(['id', 'image_path']);
        $unTransformedIndividualProducts = Product::where('is_set', false)
            ->whereNotNull('color')
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', $id);
            })->get();
        $individualProducts = $unTransformedIndividualProducts->map(function ($individualProduct){
            $individualProduct->image = url('storage/images/products/' . $individualProduct->main_image);
            return $individualProduct;
        });
        return view('admin.pages.products.edit', compact('product', 'subcategories', 'existingFiles', 'individualProducts'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Custom validation messages in Azerbaijani
        $messages = [
            'name_az.required' => 'Məhsul adı (AZ) tələb olunur.',
            'name_en.required' => 'Product name (EN) is required.',
            'name_ru.required' => 'Название продукта (RU) требуется.',
            'price.required' => 'Məhsulun qiyməti tələb olunur.',
            'price.numeric' => 'Qiymət düzgün formatda olmalıdır.',
            'discount.numeric' => 'Endirim düzgün rəqəm olmalıdır.',
            'discount.max' => 'Endirim 100%-dən çox ola bilməz.',
            'discount_ends_at.after' => 'Endirim müddəti bugündən sonrakı tarixdə olmalıdır.',
        ];

        // Validation
        $validated = $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'color' => 'nullable|string',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_ends_at' => 'nullable|date|after:today',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'selected_sub_category_id' => 'exists:subcategories,id',
            'selected_products' => 'nullable|array',
            'selected_products.*' => 'nullable|integer|min:0',
        ], $messages);

        $validated['discount'] = $validated['discount'] ?? null;
        $validated['discount_ends_at'] = $validated['discount_ends_at'] ?? null;

        try {
            // Begin transaction for atomicity
            DB::beginTransaction();

            // Update the base product
            $product->update([
                'name' => $request->name_az,
                'description' => $request->description_az,
                'sku' => $request->sku,
                'price' => $request->price,
                'stock' => $request->stock,
                'color' => $request->color ?? null,
                'discount' => $request->discount,
                'discount_ends_at' => $request->discount_ends_at,
            ]);

            $product->subcategories()->detach();
            $product->subcategories()->sync([$validated['selected_sub_category_id']]);

            // Handle main image update if provided
            $product->translations()->updateOrCreate(
                ['locale' => 'az'], [
                    'name' => $request->name_az,
                    'description' => $request->description_az
                ]
            );
            $product->translations()->updateOrCreate(
                ['locale' => 'en'], [
                    'name' => $request->name_en,
                    'description' => $request->description_en
                ]
            );
            $product->translations()->updateOrCreate(
                ['locale' => 'ru'], [
                    'name' => $request->name_ru,
                    'description' => $request->description_ru
                ]
            );

            // Handle main image update if provided
            if ($request->hasFile('main_image')) {
                $this->updateMainImage($request, $product);
            }

            // Handle multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = $image->store('images/products', 'public');
                    $filenameOnly = basename($filename);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $filenameOnly,
                    ]);
                }
            }

            // Handle deleting existing images if necessary
            if ($request->filled('existing_images')) {
                $existingImagesIds = $request->input('existing_images_ids');
                $productImagesIds = $product->allImages()->pluck('id')->toArray();
                $imageIdsToDelete = array_diff($productImagesIds, $existingImagesIds);

                foreach ($imageIdsToDelete as $imageId) {
                    $productImage = ProductImage::find($imageId);
                    if ($productImage && !$productImage->is_main) {
                        Storage::disk('public')->delete('images/products/' . $productImage->image_path);
                        $productImage->delete();
                    }
                }
            }
            if (!empty($validated['selected_products'])) {
                // Begin a transaction for better consistency
                DB::transaction(function () use ($product, $validated) {
                    // Remove the `parent_id` from all products that currently belong to the set
                    Product::where('parent_id', $product->id)->update(['parent_id' => null]);

                    // Set the `parent_id` for the selected products in one go
                    Product::whereIn('id', $validated['selected_products'])
                        ->update(['parent_id' => $product->id]);
                });
            }

            DB::commit();

            return response()->json(['message' => 'Məhsul uğurla yeniləndi!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Məhsulun yenilənməsində xəta baş verdi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle updating the main image.
     */
    protected function updateMainImage(Request $request, Product $product)
    {
        // Retrieve all existing main images
        $mainImages = $product->allImages()->where('is_main', 1)->get();

        // Delete all existing main images if any
        foreach ($mainImages as $mainImage) {
            // Delete the image file from storage
            Storage::delete('public/images/products/' . $mainImage->image_path);

            // Remove the main image record from the database
            $mainImage->delete();
        }

        // Store the new main image
        $file = $request->file('main_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/images/products/', $filename);

        // Create a new product image record for the main image
        ProductImage::create([
            'is_main' => 1,
            'product_id' => $product->id,
            'image_path' => $filename,
        ]);
    }

    /**
     * Handle updating color variations.
     */
    protected function updateColorVariations($colors, Product $product)
    {
        foreach ($colors as $color) {
            $colorProduct = Product::updateOrCreate(
                [
                    'parent_id' => $product->id,
                    'color' => $color,
                ],
                [
                    'name' => $product->name . ' (' . $color . ')',
                    'sku' => $product->sku . '-' . strtolower($color),
                    'description' => $product->description,
//                    'price' => $product->price,
//                    'stock' => $product->stock,
                    'discount' => $product->discount == 0 ? null : $product->discount,
                    'discount_ends_at' => $product->discount_ends_at,
                ]
            );

            // Sync images for the color variant
//            foreach ($product->images as $image) {
//                ProductImage::updateOrCreate(
//                    [
//                        'product_id' => $colorProduct->id,
//                        'is_main' => $image->is_main,
//                    ],
//                    [
//                        'image_path' => $image->image_path,
//                    ]
//                );
//            }
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Handle product deletion
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }



    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store file temporarily
            $path = $file->storeAs('public/uploads/temp', $filename);

            // Return the file path to the frontend
            return response()->json(['success' => true, 'filepath' => $path]);
        }

        return response()->json(['success' => false]);
    }

    public function deleteMedia(Request $request)
    {
        $filepath = $request->input('filepath');

        // Check if file exists and delete it
        if (Storage::exists($filepath)) {
            Storage::delete($filepath);
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }
}
