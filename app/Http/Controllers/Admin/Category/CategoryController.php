<?php
namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\User\User;
use App\Notifications\PushNotificationMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class CategoryController extends Controller
{
    public function index()
    {
//        $user = User::find(37); // Get the user to notify
//        $user->notify(new PushNotificationMobile('Title', 'Message body'));
        $fcmMessage = FcmMessage::create()
            ->token('f51aZHmERcadEZi6TaDFoC:APA91bH_bMFrjV_Wsep5w7G7BRQ_s-wRh5vn2xVNBc_r55eVtGv5ZxsEavhQVz3xeA23CD4l3maBLuD5oOyStw6YEZhhz071CEoE9uD6o8_7mVh_l6nnsPg')
            ->notification(
                FcmNotification::create()
                    ->title("aaaa")
                    ->body("ddddd")
            );


// Try sending the message and log any potential errors
        try {
            $response = Firebase::messaging()->send($fcmMessage);
//            $response = app('fcm')->send($fcmMessage);
            Log::info('FCM Response:', ['response' => $response]);
        } catch (\Exception $e) {
            Log::error('FCM Notification failed', ['error' => $e->getMessage()]);
        }

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
            'slug' => 'required|unique:categories,slug|max:255',
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'widget_view_image' => 'required|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'meta_title_az' => 'nullable|max:255',
            'meta_title_en' => 'nullable|max:255',
            'meta_title_ru' => 'nullable|max:255',
            'meta_description_az' => 'nullable|max:1000',
            'meta_description_en' => 'nullable|max:1000',
            'meta_description_ru' => 'nullable|max:1000',
            'description_web_az' => 'nullable',
            'description_web_en' => 'nullable',
            'description_web_ru' => 'nullable',
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

        // Create the category with 'az' language default values
        $category = Category::create([
            'name' => $request->name_az,
            'banner_image' => $bannerImageName,
            'slug' => $request->slug,
            'widget_view_image' => $widgetViewImageName,
            'description' => $request->description_az,
            'homescreen_widget' => $request->has('homescreen_widget')
        ]);

        // Create translations for each language
        $category->translations()->create([
            'locale' => 'az',
            'name' => $request->name_az,
            'description' => $request->description_az,
            'meta_title' => $request->meta_title_az,
            'meta_description' => $request->meta_description_az,
            'description_web' => $request->description_web_az
        ]);

        $category->translations()->create([
            'locale' => 'en',
            'name' => $request->name_en,
            'description' => $request->description_en,
            'meta_title' => $request->meta_title_en,
            'meta_description' => $request->meta_description_en,
            'description_web' => $request->description_web_en
        ]);

        $category->translations()->create([
            'locale' => 'ru',
            'name' => $request->name_ru,
            'description' => $request->description_ru,
            'meta_title' => $request->meta_title_ru,
            'meta_description' => $request->meta_description_ru,
            'description_web' => $request->description_web_ru
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
            'slug' => 'required|unique:categories,slug,' . $category->id . '|max:255',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'widget_view_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
            'meta_title_az' => 'nullable|max:255',
            'meta_title_en' => 'nullable|max:255',
            'meta_title_ru' => 'nullable|max:255',
            'meta_description_az' => 'nullable|max:1000',
            'meta_description_en' => 'nullable|max:1000',
            'meta_description_ru' => 'nullable|max:1000',
            'description_web_az' => 'nullable',
            'description_web_en' => 'nullable',
            'description_web_ru' => 'nullable',
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
            'name' => $request->name_az,
            'slug' => $request->slug,
            'banner_image' => $bannerImageName,
            'widget_view_image' => $widgetViewImageName,
            'description' => $request->description_az,
            'homescreen_widget' => $request->has('homescreen_widget'),
        ]);

        // Update or create translations for each language
        $category->translations()->updateOrCreate(
            ['locale' => 'az'],
            [
                'name' => $request->name_az,
                'description' => $request->description_az,
                'meta_title' => $request->meta_title_az,
                'meta_description' => $request->meta_description_az,
                'description_web' => $request->description_web_az
            ]
        );

        $category->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'name' => $request->name_en,
                'description' => $request->description_en,
                'meta_title' => $request->meta_title_en,
                'meta_description' => $request->meta_description_en,
                'description_web' => $request->description_web_en
            ]
        );

        $category->translations()->updateOrCreate(
            ['locale' => 'ru'],
            [
                'name' => $request->name_ru,
                'description' => $request->description_ru,
                'meta_title' => $request->meta_title_ru,
                'meta_description' => $request->meta_description_ru,
                'description_web' => $request->description_web_ru
            ]
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

    public function bulkDeactivate(Request $request)
    {
        // Validate the incoming category IDs
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        $categories = Category::with('subcategories')->whereIn('id', $request->category_ids)->get();

        // Array to store categories that can't be deactivated
        $undeletableCategories = [];

        foreach ($categories as $category) {
            // Check if the category has any active subcategories
            $activeSubcategories = $category->subcategories()->where('is_active', true)->get();

            if ($activeSubcategories->isNotEmpty()) {
                // If the category has active subcategories, mark it as undeletable
                $undeletableCategories[] = $category->name;
            } else {
                // Deactivate the category as it has no active subcategories
                $category->is_active = 0;
                $category->save();
            }
        }

        // If there are categories that couldn't be deactivated, return them
        if (!empty($undeletableCategories)) {
            return response()->json([
                'success' => false,
                'message' => 'Some categories could not be deactivated due to active subcategories.',
                'undeletable_categories' => $undeletableCategories
            ]);
        }

        // If all categories were successfully deactivated, return success
        return response()->json([
            'success' => true,
            'message' => 'Selected categories have been deactivated.'
        ]);
    }
}
