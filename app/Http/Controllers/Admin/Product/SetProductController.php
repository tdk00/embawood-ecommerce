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

class SetProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // You can add filtering and pagination here
        $products = Product::where('is_set', true)->get();

        $transformedNewProducts = $products->map(function ($product) {

            $product->image = url('storage/images/products/' . $product->main_image);
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'category_name' => $product->subcategories()?->first()?->name ?? "",
                'discount' => $product->discount,
                'discount_ends_at' => $product->discount_ends_at,
                'price' => $product->price,
                'final_price' => $product->final_price,
                'average_rating' => $product->average_rating,
                'is_in_basket' => $product->is_in_basket,
                'is_favorite' => $product->is_favorite,
                'badge' => url('storage/images/badge/' . $product->badge),
                'credit_cards' => $product->credit_cards
            ];
            return $productData;
        });

        return view('admin.pages.products.sets.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $unTransformedIndividualProducts = Product::where('is_set', false)->whereNull('parent_id')->get();
        $individualProducts = $unTransformedIndividualProducts->map(function ($individualProduct){
            $individualProduct->image = url('storage/images/products/' . $individualProduct->main_image);
            return $individualProduct;
        });
        $subcategories = Subcategory::all();
        return view('admin.pages.products.sets.create', compact('subcategories', 'individualProducts'));
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
            'stock.required' => 'Stok miqdarı tələb olunur.',
            'stock.integer' => 'Stok düzgün rəqəm olmalıdır.',
            'stock.min' => 'Stok sıfırdan az ola bilməz.',
            'main_image.required' => 'Əsas şəkil tələb olunur.',
            'main_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'main_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'main_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'hover_image.required' => 'Əsas şəkil tələb olunur.',
            'hover_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'hover_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'hover_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'images.*.image' => 'Yüklənmiş şəkillər yalnız jpg, jpeg, png və ya bmp formatında olmalıdır.',
            'images.*.mimes' => 'Şəkillər yalnız jpg, jpeg, png və ya bmp formatında ola bilər.',
            'images.*.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'color.string' => 'Rənglər yalnız mətn ola bilər.',
            'selected_sub_category_id' => 'Kateqoriya düzgün seçilməlidir',
        ];

        // Validation
        $validated = $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'sku' => 'required|string|max:255|unique:products',
            'color' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'main_image' => 'required|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'selected_sub_category_id' => 'exists:subcategories,id',
            'selected_products' => 'nullable|array', // Validation for selected products
            'selected_products.*' => 'nullable|integer|min:0', // Each selected product must have a quantity

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

            $product->translations()->createMany([
                [
                    'locale' => 'az',
                    'name' => $request->name_az,
                    'description' => $request->description_az
                ],
                [
                    'locale' => 'en',
                    'name' => $request->name_en,
                    'description' => $request->description_en
                ],
                [
                    'locale' => 'ru',
                    'name' => $request->name_ru,
                    'description' => $request->description_ru
                ],
            ]);


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

            if (!empty($validated['selected_products'])) {
                foreach ($validated['selected_products'] as $productRealId => $quantity) {
                    DB::table('product_set')->insert([
                        'set_id' => $product->id, // The ID of the main product
                        'product_id' => $productRealId, // The ID of the selected product
                        'quantity' => $quantity, // The selected quantity
                    ]);
                }
            }

            $product->is_set = 1;
            $product->save();


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
        $product = Product::with('images')->findOrFail($id);
        $subcategories = Subcategory::all();
        $existingFiles = $product->images()
            ->where('is_main', '!=', 1)
            ->get(['id', 'image_path']);
        $unTransformedIndividualProducts = Product::where('is_set', false)->whereNull('parent_id')->get();
        $individualProducts = $unTransformedIndividualProducts->map(function ($individualProduct){
            $individualProduct->image = url('storage/images/products/' . $individualProduct->main_image);
            return $individualProduct;
        });
        return view('admin.pages.products.sets.edit', compact('product', 'subcategories', 'existingFiles', 'individualProducts'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Custom validation messages in Azerbaijani
        $messages = [
            'sku.required' => 'SKU tələb olunur.',
            'sku.unique' => 'Bu SKU artıq mövcuddur.',
            'stock.required' => 'Stok miqdarı tələb olunur.',
            'stock.integer' => 'Stok düzgün rəqəm olmalıdır.',
            'stock.min' => 'Stok sıfırdan az ola bilməz.',
            'main_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'main_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'main_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'images.*.image' => 'Yüklənmiş şəkillər yalnız jpg, jpeg, png və ya bmp formatında olmalıdır.',
            'images.*.mimes' => 'Şəkillər yalnız jpg, jpeg, png və ya bmp formatında ola bilər.',
            'images.*.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'color.string' => 'Rəng yalnız mətn ola bilər.',
            'selected_sub_category_id' => 'Kateqoriya düzgün seçilməlidir',
        ];

        // Validation
        $validated = $request->validate([
            'name_az' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2000',
            'color' => 'nullable|string',
            'selected_sub_category_id' => 'exists:subcategories,id',
            'selected_products' => 'nullable|array', // Validation for selected products
            'selected_products.*' => 'nullable|integer|min:0', // Each selected product must have a quantity
        ], $messages);


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
                'discount' => $request->discount ?? null,
                'discount_ends_at' => $request->discount_ends_at ?? null,
            ]);

            // Remove all related subcategories and sync new ones
            $product->subcategories()->detach();
            $product->subcategories()->sync([$validated['selected_sub_category_id']]);

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


            if ($request->filled('existing_images')) {

                $existingImagesIds = $request->input('existing_images_ids');

                $productImagesIds = $product->allImages()->pluck('id')->toArray();


                $imageIdsToDelete = array_diff($productImagesIds, $existingImagesIds);

                // Loop through the images to delete
                foreach ($imageIdsToDelete as $imageId) {
                    // Find the image record in the database
                    $productImage = ProductImage::where('id', $imageId)->first();

                    // Check if the image is marked as 'is_main' before attempting deletion
                    if ($productImage && $productImage->is_main != 1 && $productImage->is_hover != 1) {
                        // Construct the full path of the image to delete from storage
                        $fullImagePath = 'images/products/' . $productImage->image_path;

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

            if (!empty($validated['selected_products'])) {
                // Delete all existing entries for this set_id before inserting new ones
                DB::table('product_set')->where('set_id', $product->id)->delete();

                foreach ($validated['selected_products'] as $productRealId => $quantity) {
                    DB::table('product_set')->insert([
                        'set_id' => $product->id, // The ID of the main product
                        'product_id' => $productRealId, // The ID of the selected product
                        'quantity' => $quantity, // The selected quantity
                    ]);
                }
            }

            $product->is_set = 1;

            $product->save();


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
        $mainImage = $product->allImages()->where('is_main', 1)->first();

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


    protected function updateHoverImage(Request $request, Product $product)
    {
        // Delete the old main image if exists
        $hoverImage = $product->allImages()->where('is_hover', 1)->first();

        // Delete the old main image if it exists
        if ($hoverImage) {
            // Delete the image file from storage
            Storage::delete('public/images/products/' . $hoverImage->image_path);

            // Remove the main image record from the database
            $hoverImage->delete();
        }

        // Store the new main image
        $file = $request->file('hover_image');
        $filename = time() . '_h_' . $file->getClientOriginalName();
        $file->storeAs('public/images/products/', $filename);

        // Create a new product image record for the main image
        ProductImage::create([
            'is_hover' => 1,
            'product_id' => $product->id,
            'image_path' => $filename,
        ]);
    }

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
