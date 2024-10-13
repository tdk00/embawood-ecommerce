<?php
namespace App\Http\Controllers\Admin\HomeSlider;

use App\Http\Controllers\Controller;
use App\Models\HomeScreen\HomeScreenSlider;
use App\Models\News\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderNewsController extends Controller
{
    public function index()
    {
        // Fetch all sliders with their related news, ordered by the current 'order' field
        $sliders = HomeScreenSlider::with('news')->orderBy('order', 'asc')->get();

        return view('admin.pages.sliders-news.index', compact('sliders'));
    }

    public function updateOrder(Request $request)
    {
        $slider = HomeScreenSlider::find($request->id);

        if ($slider) {
            $slider->order = $request->order;
            $slider->save();

            return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Slider not found.']);
    }
    public function create()
    {
        return view('admin.pages.sliders-news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_az' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'required|string',
            'content_az' => 'required|string',
            'content_en' => 'required|string',
            'content_ru' => 'required|string',
            'banner_image' => 'required|image',
            'slider_image' => 'required|image',
            'is_active' => 'required|boolean',
        ]);

        try {

            $news = new News();


            $news->title = $request->input('title_az');
            $news->content = $request->input('content_az'); // Now the content from Quill is passed correctly

            $banner_image = $request->file('banner_image');
            $banner_image_name = time() . '_' . $banner_image->getClientOriginalName();
            $banner_image->move(public_path('storage/images/news/'), $banner_image_name);
            $news->banner_image = $banner_image_name;

            $news->save();

            // Save translations
            $news->translations()->create(['locale' => 'az', 'title' => $request->input('title_az'), 'content' => $request->input('content_az')]);
            $news->translations()->create(['locale' => 'en', 'title' => $request->input('title_en'), 'content' => $request->input('content_en')]);
            $news->translations()->create(['locale' => 'ru', 'title' => $request->input('title_ru'), 'content' => $request->input('content_ru')]);

            // Save the slider
            $slider = new HomeScreenSlider();
            $slider->news_id = $news->id;

            $slider_image = $request->file('slider_image');
            $slider_image_name = time() . '_' . $slider_image->getClientOriginalName();
            $slider_image->move(public_path('storage/images/home_screen/sliders/'), $slider_image_name);
            $slider->slider_image = $slider_image_name;

            $slider->is_active = $request->input('is_active');
            $slider->save();

            return redirect()->route('admin.sliders-news.index')->with('success', 'Slider and News added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while saving the news.');
        }
    }



    public function edit($id)
    {
        // Find the slider with related news and translations
        $slider = HomeScreenSlider::with('news.translations')->findOrFail($id);

        return view('admin.pages.sliders-news.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        // Find the slider with its related news and translations
        $slider = HomeScreenSlider::with('news.translations')->findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'title_az' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'required|string',
            'content_az' => 'required|string',
            'content_en' => 'required|string',
            'content_ru' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Banner image is optional
            'slider_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);

        // Update news data
        $news = $slider->news;

        // Handle optional banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete the old banner image if it exists
            if ($news->banner_image && file_exists(public_path('storage/images/news/' . $news->banner_image))) {
                unlink(public_path('storage/images/news/' . $news->banner_image));
            }

            // Save the new banner image
            $banner_image = $request->file('banner_image');
            $banner_image_name = time() . '_' . $banner_image->getClientOriginalName();
            $banner_image->move(public_path('storage/images/news/'), $banner_image_name);
            $news->banner_image = $banner_image_name;
        }

        $news->save();  // Save the updated news

        // Update translations for each language
        $news->translations()->updateOrCreate(
            ['locale' => 'az'],
            ['title' => $request->input('title_az'), 'content' => $request->input('content_az')]
        );
        $news->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['title' => $request->input('title_en'), 'content' => $request->input('content_en')]
        );
        $news->translations()->updateOrCreate(
            ['locale' => 'ru'],
            ['title' => $request->input('title_ru'), 'content' => $request->input('content_ru')]
        );

        // Handle optional slider image upload
        if ($request->hasFile('slider_image')) {
            // Delete the old slider image if it exists
            if ($slider->slider_image && file_exists(public_path('storage/images/home_screen/sliders/' . $slider->slider_image))) {
                unlink(public_path('storage/images/home_screen/sliders/' . $slider->slider_image));
            }

            // Save the new slider image
            $slider_image = $request->file('slider_image');
            $slider_image_name = time() . '_' . $slider_image->getClientOriginalName();
            $slider_image->move(public_path('storage/images/home_screen/sliders/'), $slider_image_name);
            $slider->slider_image = $slider_image_name;
        }

        // Update slider activation status
        $slider->is_active = $request->input('is_active');
        $slider->save();  // Save the updated slider

        return redirect()->route('admin.sliders-news.index')->with('success', 'Slider and News updated successfully.');
    }

    public function destroy($id)
    {
        // Find the slider by ID, along with its related news
        $slider = HomeScreenSlider::with('news.translations')->findOrFail($id);

        // Begin a database transaction to ensure atomicity
        DB::transaction(function () use ($slider) {
            // Get the associated news
            $news = $slider->news;

            // Delete banner image if it exists
            if ($news->banner_image && file_exists(public_path('storage/images/news/' . $news->banner_image))) {
                unlink(public_path('storage/images/news/' . $news->banner_image));
            }

            // Delete slider image if it exists
            if ($slider->slider_image && file_exists(public_path('storage/images/home_screen/sliders/' . $slider->slider_image))) {
                unlink(public_path('storage/images/home_screen/sliders/' . $slider->slider_image));
            }

            // Delete the news translations first (if applicable)
            $news->translations()->delete();

            // Delete the news record itself
            $news->delete();

            // Delete the slider
            $slider->delete();
        });

        // Return success response
        return redirect()->route('admin.sliders-news.index')->with('success', 'Slider and related News deleted successfully.');
    }
}
