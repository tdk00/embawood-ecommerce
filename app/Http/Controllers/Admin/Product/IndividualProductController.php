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

        $products->map(function ($product) {

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
            'slug' => 'required|unique:products,slug|max:255',
            'name_az' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'meta_title_az' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_ru' => 'nullable|string|max:255',
            'meta_description_az' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
            'meta_description_ru' => 'nullable|string',
            'description_web_az' => 'nullable|string',
            'description_web_en' => 'nullable|string',
            'description_web_ru' => 'nullable|string',
            'color' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_ends_at' => 'nullable|date|after:today',
            'main_image' => 'required|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'selected_sub_category_id' => 'exists:subcategories,id',
        ], $messages);

        try {
            // Create the base product
            $product = Product::create([
                'slug' => $request->slug,
                'name' => $request->name_az,
                'description' => $request->description_az,
                'color' => $request->color ?? null,
                'price' => $request->price,
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
                    'meta_title' => $request->meta_title_az,
                    'meta_description' => $request->meta_description_az,
                    'description_web' => $request->description_web_az,
                ],
                [
                    'locale' => 'en',
                    'name' => $request->name_en,
                    'description' => $request->description_en,
                    'meta_title' => $request->meta_title_en,
                    'meta_description' => $request->meta_description_en,
                    'description_web' => $request->description_web_en,
                ],
                [
                    'locale' => 'ru',
                    'name' => $request->name_ru,
                    'description' => $request->description_ru,
                    'meta_title' => $request->meta_title_ru,
                    'meta_description' => $request->meta_description_ru,
                    'description_web' => $request->description_web_ru,
                ],
            ]);

            // Handle the main image
            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/products/', $filename);
                $product->main_image = $filename;
                $product->save();
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
            'slug' => 'required|unique:products,slug,' . $product->id . '|max:255',
            'name_az' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'meta_title_az' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_ru' => 'nullable|string|max:255',
            'meta_description_az' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
            'meta_description_ru' => 'nullable|string',
            'description_web_az' => 'nullable|string',
            'description_web_en' => 'nullable|string',
            'description_web_ru' => 'nullable|string',
            'color' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_ends_at' => 'nullable|date|after:today',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
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
                'slug' => $request->slug,
                'name' => $request->name_az,
                'description' => $request->description_az,
                'price' => $request->price,
                'color' => $request->color ?? null,
                'discount' => $request->discount,
                'discount_ends_at' => $request->discount_ends_at,
            ]);

            $product->subcategories()->detach();
            $product->subcategories()->sync([$validated['selected_sub_category_id']]);

            // Handle main image update if provided
            $product->translations()->updateOrCreate(
                ['locale' => 'az'],
                [
                    'name' => $request->name_az,
                    'description' => $request->description_az,
                    'meta_title' => $request->meta_title_az,
                    'meta_description' => $request->meta_description_az,
                    'description_web' => $request->description_web_az,
                ]
            );
            $product->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'name' => $request->name_en,
                    'description' => $request->description_en,
                    'meta_title' => $request->meta_title_en,
                    'meta_description' => $request->meta_description_en,
                    'description_web' => $request->description_web_en,
                ]
            );
            $product->translations()->updateOrCreate(
                ['locale' => 'ru'],
                [
                    'name' => $request->name_ru,
                    'description' => $request->description_ru,
                    'meta_title' => $request->meta_title_ru,
                    'meta_description' => $request->meta_description_ru,
                    'description_web' => $request->description_web_ru,
                ]
            );

            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/products/', $filename);
                $product->main_image = $filename;
                $product->save();
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
//                    'sku' => $product->sku . '-' . strtolower($color),
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


    public function bulkDiscount(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'discount_end_time' => 'nullable|date|after:today',
        ]);

        $productIds = $request->input('product_ids');
        $discountPercentage = $request->input('discount_percentage');
        $discountEndTime = $request->discount_end_time ?? null;

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->update([
                    'discount' => $discountPercentage,
                    'discount_ends_at' => $discountEndTime
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Discount applied to selected products.'
        ]);
    }



    public function bulkDeactivate(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
        ]);

        $productIds = $request->input('product_ids');

        foreach ($productIds as $productId) {

            DB::table('basket_items')
                ->where('product_id', $productId)
                ->orWhere('set_id', $productId)
                ->delete();

            DB::table('favorites')->where('product_id', $productId)->delete();

            // Remove from most_viewed_products
            DB::table('most_viewed_products')->where('product_id', $productId)->delete();

            // Remove from new_products
            DB::table('new_products')->where('product_id', $productId)->delete();

            // Remove from product_purchased_together (both product_id and purchased_together_product_id)
            DB::table('product_purchased_together')
                ->where('product_id', $productId)
                ->orWhere('purchased_together_product_id', $productId)
                ->delete();

            // Remove from product_set
            DB::table('product_set')->where('product_id', $productId)->delete();

            // Remove from product_similar (both product_id and similar_product_id)
            DB::table('product_similar')
                ->where('product_id', $productId)
                ->orWhere('similar_product_id', $productId)
                ->delete();

            // Remove from product_subcategory
            DB::table('product_subcategory')->where('product_id', $productId)->delete();

            // Remove from product_sub_idea
            DB::table('product_sub_idea')->where('product_id', $productId)->delete();

            DB::table('product_sub_idea_item')->where('product_id', $productId)->delete();

            DB::table('products')->where('id', $productId)->update(['is_active' => 0]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Selected products have been deactivated and associated data removed.'
        ]);
    }
}
