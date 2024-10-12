<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\AboutUs;
use Illuminate\Http\Request;

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
        ]);

        // Get the "About Us" instance
        $aboutUs = AboutUs::firstOrCreate([]);

        // Save translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $translation = $aboutUs->translations()->firstOrNew(['locale' => $locale]);
            $translation->title = $request->input("title_{$locale}");
            $translation->description = $request->input("description_{$locale}");
            $translation->save();
        }

        return redirect()->route('admin.about-us.edit')->with('success', 'About Us updated successfully');
    }
}
