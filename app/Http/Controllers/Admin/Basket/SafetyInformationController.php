<?php

namespace App\Http\Controllers\Admin\Basket;

use App\Http\Controllers\Controller;
use App\Models\Basket\SafetyInformation;
use Illuminate\Http\Request;


class SafetyInformationController extends Controller
{
    public function index()
    {
        // Fetch all safety information along with translations
        $safetyInformations = SafetyInformation::with('translations')->orderBy('id', 'desc')->get();
        return view('admin.pages.safety_informations.index', compact('safetyInformations'));
    }

    public function create()
    {
        return view('admin.pages.safety_informations.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title_az' => 'required|max:255',
            'title_en' => 'required|max:255',
            'title_ru' => 'required|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
        ]);


        $icon = null;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->getClientOriginalName();
            $request->file('icon')->storeAs('images/icons', $icon, 'public');
        }

        // Create the SafetyInformation
        $safetyInformation = SafetyInformation::create([
            'icon' => $icon,
            'title' => $request->title_az,
            'description' => $request->description_az,
        ]);

        // Create translations for each language
        $safetyInformation->translations()->create([
            'locale' => 'az',
            'title' => $request->title_az,
            'description' => $request->description_az,
        ]);

        $safetyInformation->translations()->create([
            'locale' => 'en',
            'title' => $request->title_en,
            'description' => $request->description_en,
        ]);

        $safetyInformation->translations()->create([
            'locale' => 'ru',
            'title' => $request->title_ru,
            'description' => $request->description_ru,
        ]);

        return redirect()->route('admin.safety-informations.index')
            ->with('success', 'Safety Information created successfully.');
    }

    public function edit(SafetyInformation $safetyInformation)
    {
        $safetyInformation->load('translations');
        return view('admin.pages.safety_informations.edit', compact('safetyInformation'));
    }

    public function update(Request $request, SafetyInformation $safetyInformation)
    {
        // Validate the incoming request
        $request->validate([
            'title_az' => 'required|max:255',
            'title_en' => 'required|max:255',
            'title_ru' => 'required|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_az' => 'nullable',
            'description_en' => 'nullable',
            'description_ru' => 'nullable',
        ]);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('icons', 'public');
        } else {
            $icon = $safetyInformation->icon;
        }

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $iconImageName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/icons', $iconImageName, 'public');
        } else {
            $iconImageName = $safetyInformation->icon;
        }

        // Update the SafetyInformation
        $safetyInformation->update(['icon' => $iconImageName]);

        // Update or create translations for each language
        $safetyInformation->translations()->updateOrCreate(
            ['locale' => 'az'],
            ['title' => $request->title_az, 'description' => $request->description_az]
        );

        $safetyInformation->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['title' => $request->title_en, 'description' => $request->description_en]
        );

        $safetyInformation->translations()->updateOrCreate(
            ['locale' => 'ru'],
            ['title' => $request->title_ru, 'description' => $request->description_ru]
        );

        return redirect()->route('admin.safety-informations.index')
            ->with('success', 'Safety Information updated successfully.');
    }

    public function destroy(SafetyInformation $safetyInformation)
    {
        $safetyInformation->delete();
        return redirect()->route('admin.safety-informations.index')
            ->with('success', 'Safety Information deleted successfully.');
    }
}
