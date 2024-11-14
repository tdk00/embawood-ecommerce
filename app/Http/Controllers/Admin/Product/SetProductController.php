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
            'main_image.required' => 'Əsas şəkil tələb olunur.',
            'main_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'main_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'main_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'images.*.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'color.string' => 'Rənglər yalnız mətn ola bilər.',
            'selected_sub_category_id' => 'Kateqoriya düzgün seçilməlidir',
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
            'main_image' => 'required|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'selected_sub_category_id' => 'exists:subcategories,id',
            'selected_products' => 'nullable|array', // Validation for selected products
            'selected_products.*' => 'nullable|integer|min:0', // Each selected product must have a quantity

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


            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/products/', $filename);
                $product->main_image = $filename;
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
            'main_image.image' => 'Əsas şəkil düzgün formatda olmalıdır (jpg, jpeg, png, bmp).',
            'main_image.mimes' => 'Əsas şəkil formatı yalnız jpg, jpeg, png, bmp ola bilər.',
            'main_image.max' => 'Şəklin ölçüsü maksimum 2MB ola bilər.',
            'color.string' => 'Rəng yalnız mətn ola bilər.',
            'selected_sub_category_id' => 'Kateqoriya düzgün seçilməlidir',
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
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'badge_file' => 'nullable|image|mimes:svg|max:2000',
            'badge_file_2' => 'nullable|image|mimes:svg|max:2000',
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
                'slug' => $request->slug,
                'name' => $request->name_az,
                'description' => $request->description_az,
                'price' => $request->price,
                'color' => $request->color ?? null,
                'discount' => $request->discount ?? null,
                'discount_ends_at' => $request->discount_ends_at ?? null,
            ]);

            // Remove all related subcategories and sync new ones
            $product->subcategories()->detach();
            $product->subcategories()->sync([$validated['selected_sub_category_id']]);

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



            if ($request->hasFile('badge_file')) {
//                Storage::delete('public/images/badge/' . $product->badge_1);
                $file = $request->file('badge_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/badge/', $filename);
                $product->update([
                    'badge_1' => $filename
                ]);

            }

            // Handle hover image update if provided
            if ($request->hasFile('badge_file_2')) {
//                Storage::delete('public/images/badge/' . $product->badge_1);
                $file = $request->file('badge_file_2');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/images/badge/', $filename);
                $product->update([
                    'badge_2' => $filename
                ]);
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

    public function destroy(Product $product)
    {
        // Handle product deletion
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
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
