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
            ->paginate(10); // Adjust per page limit as needed

        return view('admin.products.index', compact('products'));
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
            'name.required' => 'Məhsul adı tələb olunur.',
            'sku.required' => 'SKU tələb olunur.',
            'sku.unique' => 'Bu SKU artıq mövcuddur.',
            'price.required' => 'Məhsulun qiyməti tələb olunur.',
            'price.numeric' => 'Qiymət düzgün formatda olmalıdır.',
            'price.min' => 'Qiymət sıfırdan az ola bilməz.',
            'stock.required' => 'Stok miqdarı tələb olunur.',
            'stock.integer' => 'Stok düzgün rəqəm olmalıdır.',
            'stock.min' => 'Stok sıfırdan az ola bilməz.',
            'discount.numeric' => 'Endirim düzgün rəqəm olmalıdır.',
            'discount.min' => 'Endirim sıfırdan az ola bilməz.',
            'discount.max' => 'Endirim 100%-dən çox ola bilməz.',
            'discount_ends_at.after' => 'Endirim müddəti bugündən sonrakı tarixdə olmalıdır.',
            'main_image.required' => 'Əsas şəkil tələb olunur.',
            'main_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'main_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'main_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'images.*.image' => 'Yüklənmiş şəkillər yalnız jpg, jpeg, png və ya bmp formatında olmalıdır.',
            'images.*.mimes' => 'Şəkillər yalnız jpg, jpeg, png və ya bmp formatında ola bilər.',
            'images.*.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'colors.*.string' => 'Rənglər yalnız mətn ola bilər.',
            'selected_sub_category_id' => 'Kateqoriya düzgün seçilməlidir',
        ];

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_ends_at' => 'nullable|date|after:today',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'main_image' => 'required|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'uploaded_files' => 'nullable|string',
            'selected_sub_category_id' => 'exists:subcategories,id',
        ], $messages);

        try {
            // Create the base product
            $product = Product::create($validated);

            $product->subcategories()->attach($validated['selected_sub_category_id']);

            // Handling uploaded files
            if (!empty($validated['uploaded_files'])) {
                $uploadedFiles = json_decode($validated['uploaded_files'], true);

                if ($uploadedFiles) {
                    foreach ($uploadedFiles as $filePath) {
                        $filename = basename($filePath);
                        $newPath = 'public/images/products/' . $filename;
                        Storage::move($filePath, $newPath);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $filename,
                        ]);
                    }
                }
            }

            // Handling the main image
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

            // Create color variations
            if (!empty($validated['colors'])) {
                foreach ($validated['colors'] as $color) {
                    $colorProduct = Product::create([
                        'parent_id' => $product->id,
                        'name' => $product->name . ' (' . $color . ')',
                        'sku' => $product->sku . '-' . strtolower($color),
                        'description' => $product->description,
                        'price' => $product->price,
                        'stock' => $product->stock,
                        'discount' => $product->discount == 0 ? null : $product->discount,
                        'discount_ends_at' => $product->discount_ends_at,
                        'color' => $color,
                    ]);

                    foreach ($product->images as $image) {
                        ProductImage::create([
                            'product_id' => $colorProduct->id,
                            'is_main' => $image->is_main,
                            'image_path' => $image->image_path,
                        ]);
                    }
                }
            }

            return response()->json(['message' => 'Məhsul və rəng variantları uğurla yaradıldı!']);
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
        return view('admin.pages.products.edit', compact('product', 'subcategories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Custom validation messages in Azerbaijani
        $messages = [
            'name.required' => 'Məhsul adı tələb olunur.',
            'sku.required' => 'SKU tələb olunur.',
            'sku.unique' => 'Bu SKU artıq mövcuddur.',
            'price.required' => 'Məhsulun qiyməti tələb olunur.',
            'price.numeric' => 'Qiymət düzgün formatda olmalıdır.',
            'price.min' => 'Qiymət sıfırdan az ola bilməz.',
            'stock.required' => 'Stok miqdarı tələb olunur.',
            'stock.integer' => 'Stok düzgün rəqəm olmalıdır.',
            'stock.min' => 'Stok sıfırdan az ola bilməz.',
            'discount.numeric' => 'Endirim düzgün rəqəm olmalıdır.',
            'discount.min' => 'Endirim sıfırdan az ola bilməz.',
            'discount.max' => 'Endirim 100%-dən çox ola bilməz.',
            'discount_ends_at.after' => 'Endirim müddəti bugündən sonrakı tarixdə olmalıdır.',
            'main_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'main_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'main_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'images.*.image' => 'Yüklənmiş şəkillər yalnız jpg, jpeg, png və ya bmp formatında olmalıdır.',
            'images.*.mimes' => 'Şəkillər yalnız jpg, jpeg, png və ya bmp formatında ola bilər.',
            'images.*.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'colors.*.string' => 'Rənglər yalnız mətn ola bilər.',
            'selected_sub_category_id' => 'Kateqoriya düzgün seçilməlidir',
        ];

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_ends_at' => 'nullable|date|after:today',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'uploaded_files' => 'nullable|string',
            'selected_sub_category_id' => 'exists:subcategories,id',
        ], $messages);

        $validated['discount'] = $validated['discount'] ?? null;
        $validated['discount_ends_at'] = $validated['discount_ends_at'] ?? null;

        try {
            // Begin transaction for atomicity
            DB::beginTransaction();

            // Update the base product
            $product->update($validated);

            // Remove all related subcategories and sync new ones
            $product->subcategories()->detach();
            $product->subcategories()->sync([$validated['selected_sub_category_id']]);

            // Handle main image update if provided
            if ($request->hasFile('main_image')) {
                $this->updateMainImage($request, $product);
            }


            if ($request->filled('existing_images')) {
                // Get all the existing image paths from the request
                $existingImages = $request->input('existing_images');

                // Fetch all current images associated with the product from the database
                $productImages = $product->images()->pluck('image_path')->toArray();

                // Find images that are in the database but no longer in the request (i.e., marked for deletion)
                $imagesToDelete = array_diff($productImages, $existingImages);

                // Loop through the images to delete
                foreach ($imagesToDelete as $image) {
                    // Find the image record in the database
                    $productImage = ProductImage::where('image_path', $image)->first();

                    // Check if the image is marked as 'is_main' before attempting deletion
                    if ($productImage && $productImage->is_main != 1) {
                        // Construct the full path of the image to delete from storage
                        $fullImagePath = 'images/products/' . $image;

                        // Delete the file from storage
                        Storage::disk('public')->delete($fullImagePath);

                        // Remove the image record from the database
                        $productImage->delete();
                    }
                }
            }


            if ($request->hasFile('images')) {
                // Loop through the uploaded files and save them
                foreach ($request->file('images') as $image) {
                    // Store the image in the desired path (e.g., 'public/storage/images/products')
                    $filename = $image->store('images/products', 'public');
                    $filenameOnly = basename($filename); // Get only the file name

                    // Save the file name (without the path) into the database
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $filenameOnly, // Save only the file name
                    ]);
                }
            }

// Check if there are images to delete (old images passed by file name)


            // Update color variations
            if (!empty($validated['colors'])) {
                $this->updateColorVariations($validated['colors'], $product);
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
        // Delete the old main image if exists
        $mainImage = $product->images()->where('is_main', 1)->first();

        // Delete the old main image if it exists
        if ($mainImage) {
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
