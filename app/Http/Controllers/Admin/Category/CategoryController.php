<?php
namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.pages.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.pages.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_az' => 'required|max:255',
            'name_en' => 'required|max:255',
            'name_ru' => 'required|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'widget_view_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'homescreen_widget' => 'boolean'
        ]);

        // Handle banner image upload
        $bannerImageName = null;
        if ($request->hasFile('banner_image')) {
            $bannerImageName = $request->file('banner_image')->getClientOriginalName();
            $request->file('banner_image')->storeAs('images/category/banner', $bannerImageName, 'public');
        }

        // Handle widget view image upload
        $widgetViewImageName = null;
        if ($request->hasFile('widget_view_image')) {
            $widgetViewImageName = $request->file('widget_view_image')->getClientOriginalName();
            $request->file('widget_view_image')->storeAs('images/category/widget_images', $widgetViewImageName, 'public');
        }

        // Create the category, storing the 'az' language name in the 'name' field
        $category = Category::create([
            'name' => $request->name_az,
            'banner_image' => $bannerImageName,
            'widget_view_image' => $widgetViewImageName,
            'description' => $request->description_az, // Store 'description_az' in the 'description' field
            'homescreen_widget' => $request->has('homescreen_widget')
        ]);

        // Create translations for each language
        $category->translations()->create([
            'locale' => 'az',
            'name' => $request->name_az,
            'description' => $request->description_az,
        ]);

        $category->translations()->create([
            'locale' => 'en',
            'name' => $request->name_en,
            'description' => $request->description_en,
        ]);

        $category->translations()->create([
            'locale' => 'ru',
            'name' => $request->name_ru,
            'description' => $request->description_ru,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::with('translations')->find($id);
        $category->banner_image = url('storage/images/category/banner/' . $category->banner_image);
        $category->widget_view_image = url('storage/images/category/widget_images/' . $category->widget_view_image);

        return view('admin.pages.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_az' => 'required|max:255',
            'name_en' => 'required|max:255',
            'name_ru' => 'required|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'widget_view_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'homescreen_widget' => 'boolean'
        ]);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $bannerImageName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/category/banner', $bannerImageName, 'public');
        } else {
            $bannerImageName = $category->banner_image;
        }

        if ($request->hasFile('widget_view_image')) {
            $file = $request->file('widget_view_image');
            $widgetViewImageName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/category/widget_images', $widgetViewImageName, 'public');
        } else {
            $widgetViewImageName = $category->widget_view_image;
        }

        // Update the category with 'az' language values
        $category->update([
            'name' => $request->name_az, // Storing the 'az' name in the main 'name' field
            'banner_image' => $bannerImageName,
            'widget_view_image' => $widgetViewImageName,
            'description' => $request->description_az, // Storing 'az' description in the main 'description' field
            'homescreen_widget' => $request->has('homescreen_widget'),
        ]);

        // Update or create translations for each language
        $category->translations()->updateOrCreate(
            ['locale' => 'az'],
            ['name' => $request->name_az, 'description' => $request->description_az]
        );

        $category->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['name' => $request->name_en, 'description' => $request->description_en]
        );

        $category->translations()->updateOrCreate(
            ['locale' => 'ru'],
            ['name' => $request->name_ru, 'description' => $request->description_ru]
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function updateOrder(Request $request)
    {
        $category = Category::find($request->id);
        if ($category) {
            $category->order = $request->order;
            $category->save();

            return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Category not found.']);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
