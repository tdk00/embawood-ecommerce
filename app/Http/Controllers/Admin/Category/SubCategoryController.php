<?php
namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\Subcategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the subcategories.
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderBy('id', 'desc')->get();
        return view('admin.pages.subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new subcategory.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.pages.subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created subcategory in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_az' => 'required|max:255',
            'name_en' => 'required|max:255',
            'name_ru' => 'required|max:255',
            'slug' => 'required|unique:subcategories,slug|max:255',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'meta_title_az' => 'nullable|max:255',
            'meta_description_az' => 'nullable',
            'description_web_az' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
        ]);

        // Handle image uploads
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('images/subcategories/small', $image, 'public');
        }

        // Create the subcategory
        $subcategory = Subcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name_az,
            'slug' => $request->slug,
            'description' => $request->description_az,
            'image' => $image,
            'homescreen_widget' => 0,
        ]);

        // Create translations for each language
        foreach (['az', 'en', 'ru'] as $locale) {
            $subcategory->translations()->create([
                'locale' => $locale,
                'name' => $request->input("name_{$locale}"),
                'description' => $request->input("description_{$locale}"),
                'meta_title' => $request->input("meta_title_{$locale}"),
                'meta_description' => $request->input("meta_description_{$locale}"),
                'description_web' => $request->input("description_web_{$locale}"),
            ]);
        }

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    /**
     * Show the form for editing the specified subcategory.
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();

        // Load the translations for the subcategory
        $subcategory->load('translations');

        return view('admin.pages.subcategories.create', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified subcategory in the database.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_az' => 'required|max:255',
            'name_en' => 'required|max:255',
            'name_ru' => 'required|max:255',
            'slug' => 'required|unique:subcategories,slug,' . $subcategory->id . '|max:255',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'meta_title_az' => 'nullable|max:255',
            'meta_description_az' => 'nullable',
            'description_web_az' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240'
        ]);

        // Handle image uploads
        $image = $subcategory->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('images/subcategories/small', $image, 'public');
        }

        // Update the subcategory
        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name_az,
            'slug' => $request->slug,
            'description' => $request->description_az,
            'image' => $image,
            'homescreen_widget' => 0,
        ]);

        // Update or create translations for each language
        foreach (['az', 'en', 'ru'] as $locale) {
            $subcategory->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $request->input("name_{$locale}"),
                    'description' => $request->input("description_{$locale}"),
                    'meta_title' => $request->input("meta_title_{$locale}"),
                    'meta_description' => $request->input("meta_description_{$locale}"),
                    'description_web' => $request->input("description_web_{$locale}"),
                ]
            );
        }

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    public function updateSubcategoryOrder(Request $request)
    {
        $subcategory = Subcategory::find($request->id);
        if ($subcategory) {
            $subcategory->order = $request->order;
            $subcategory->save();

            return response()->json(['success' => true, 'message' => 'Subcategory order updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Subcategory not found.']);
    }

    public function applyDiscountToProducts(Request $request, $subcategoryId)
    {
        $subcategory = Subcategory::findOrFail($subcategoryId);
        $discount = $request->input('discount');
        $discountEndsAt = $request->input('discount_ends_at') ?: null;

        // Apply discount to all products in the subcategory where is_set is false
        $subcategory->products()->where('is_set', false)->update([
            'discount' => $discount,
            'discount_ends_at' => $discountEndsAt, // Will be null if not provided
        ]);

        return redirect()->route('admin.subcategories.index')->with('success', 'Discount applied successfully.');
    }

    public function applyBadgeToProducts(Request $request, $subcategoryId)
    {
        $messages = [
        ];

        $validated = $request->validate([
            'badge_file' => 'nullable|image|mimes:svg|max:2000',
            'badge_file_2' => 'nullable|image|mimes:svg|max:2000',
        ], $messages);
        $subcategory = Subcategory::findOrFail($subcategoryId);

        if ($request->hasFile('badge_file')) {
//                Storage::delete('public/images/badge/' . $product->badge_1);
            $file = $request->file('badge_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/images/badge/', $filename);
            $subcategory->products()->update([
                'badge_1' => $filename, // Will be null if not provided
            ]);

        }

        // Handle hover image update if provided
        if ($request->hasFile('badge_file_2')) {
//                Storage::delete('public/images/badge/' . $product->badge_1);
            $file = $request->file('badge_file_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/images/badge/', $filename);
            $subcategory->products()->update([
                'badge_2' => $filename, // Will be null if not provided
            ]);
        }

        return redirect()->route('admin.subcategories.index')->with('success', 'Stiker şəkli yeniləndi.');
    }

    public function bulkDeactivate(Request $request)
    {
        // Validate the input
        $request->validate([
            'migrate_to_subcategory_id' => 'required|exists:subcategories,id',
            'selected_subcategories' => 'required|array',
            'selected_subcategories.*' => 'exists:subcategories,id'
        ]);

        if (in_array($request->migrate_to_subcategory_id, $request->selected_subcategories)) {
            return response()->json([
                'success' => false,
                'message' => 'The subcategory to migrate products to cannot be one of the subcategories being deactivated.'
            ], 400);
        }

        // Get the target subcategory for migration
        $targetSubcategory = Subcategory::findOrFail($request->migrate_to_subcategory_id);

        // Get the selected subcategories for deactivation
        $subcategories = Subcategory::whereIn('id', $request->selected_subcategories)->get();

        foreach ($subcategories as $subcategory) {
            // Get products associated with the current subcategory
            $products = $subcategory->products;

            foreach ($products as $product) {
                // Sync (replace) the product's subcategories with the target subcategory
                // This will detach the product from all other subcategories except the new target subcategory
                $product->subcategories()->sync([$targetSubcategory->id]);
            }

            // Deactivate the subcategory after migration
            $subcategory->is_active = 0;
            $subcategory->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Products migrated and selected subcategories deactivated successfully.'
        ]);
    }
}
