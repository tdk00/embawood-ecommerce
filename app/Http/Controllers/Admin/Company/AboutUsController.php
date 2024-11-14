<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutUsController extends Controller
{
    public function edit()
    {
        // Get the single "About Us" instance
        $aboutUs = AboutUs::with('translations')->firstOrCreate([]);
        return view('admin.pages.company.about_us.edit', compact('aboutUs'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title_az' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'required|string',
            'description_az' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'description_web' => 'nullable|string', // Validation for description_web
        ]);

        // Get the "About Us" instance
        $aboutUs = AboutUs::firstOrCreate([]);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Generate a unique name for the image
            $bannerImageName = time() . '_' . Str::random(10) . '.' . $request->file('banner_image')->getClientOriginalExtension();

            // Store the image in the 'public/images/about/banner' directory
            $request->file('banner_image')->storeAs('images/category/banner/', $bannerImageName, 'public');

            // Update the banner_image path in the database
            $aboutUs->banner_image = $bannerImageName;
        }

        // Save translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $translation = $aboutUs->translations()->firstOrNew(['locale' => $locale]);
            $translation->title = $request->input("title_{$locale}");
            $translation->description = $request->input("description_{$locale}");
            $translation->description_web = $request->input("description_web_{$locale}");
            $translation->save();
        }

        // Save the main AboutUs instance with updated fields
        $aboutUs->save();

        return redirect()->route('admin.about-us.edit')->with('success', 'About Us updated successfully');
    }
}
