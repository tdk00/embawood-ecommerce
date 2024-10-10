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
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'widget_view_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'homescreen_widget' => 'boolean',
        ]);

        // Handle image uploads
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('images/subcategories/small', $image, 'public');
        }

        $bannerImage = null;
        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')->getClientOriginalName();
            $request->file('banner_image')->storeAs('images/subcategories/banner', $bannerImage, 'public');
        }

        $widgetViewImage = null;
        if ($request->hasFile('widget_view_image')) {
            $widgetViewImage = $request->file('widget_view_image')->getClientOriginalName();
            $request->file('widget_view_image')->storeAs('images/subcategories/homescreen', $widgetViewImage, 'public');
        }

        // Create the subcategory
        $subcategory = Subcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name_az,
            'description' => $request->description_az,
            'image' => $image,
            'banner_image' => $bannerImage,
            'widget_view_image' => $widgetViewImage,
            'homescreen_widget' => $request->has('homescreen_widget'),
        ]);

        // Create translations for each language
        $subcategory->translations()->create([
            'locale' => 'az',
            'name' => $request->name_az,
            'description' => $request->description_az,
        ]);

        $subcategory->translations()->create([
            'locale' => 'en',
            'name' => $request->name_en,
            'description' => $request->description_en,
        ]);

        $subcategory->translations()->create([
            'locale' => 'ru',
            'name' => $request->name_ru,
            'description' => $request->description_ru,
        ]);

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
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'widget_view_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'homescreen_widget' => 'boolean',
        ]);

        // Handle image uploads
        $image = $subcategory->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('images/subcategories/small', $image, 'public');
        }

        $bannerImage = $subcategory->banner_image;
        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')->getClientOriginalName();
            $request->file('banner_image')->storeAs('images/subcategories/banner', $bannerImage, 'public');
        }

        $widgetViewImage = $subcategory->widget_view_image;
        if ($request->hasFile('widget_view_image')) {
            $widgetViewImage = $request->file('widget_view_image')->getClientOriginalName();
            $request->file('widget_view_image')->storeAs('images/subcategories/homescreen', $widgetViewImage, 'public');
        }

        // Update the subcategory
        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name_az,
            'description' => $request->description_az,
            'image' => $image,
            'banner_image' => $bannerImage,
            'widget_view_image' => $widgetViewImage,
            'homescreen_widget' => $request->has('homescreen_widget'),
        ]);

        // Update or create translations for each language
        $subcategory->translations()->updateOrCreate(
            ['locale' => 'az'],
            ['name' => $request->name_az, 'description' => $request->description_az]
        );

        $subcategory->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['name' => $request->name_en, 'description' => $request->description_en]
        );

        $subcategory->translations()->updateOrCreate(
            ['locale' => 'ru'],
            ['name' => $request->name_ru, 'description' => $request->description_ru]
        );

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

    /**
     * Remove the specified subcategory from the database.
     */
    public function destroy(Subcategory $subcategory)
    {
        // Delete the subcategory
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }
}
