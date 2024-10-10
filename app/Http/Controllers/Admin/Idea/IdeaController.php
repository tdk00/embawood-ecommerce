<?php

namespace App\Http\Controllers\Admin\Idea;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use Illuminate\Http\Request;

class IdeaController extends Controller
{
    public function index()
    {
        $ideas = Idea::with('translations')->get();
        return view('admin.pages.ideas.index', compact('ideas'));
    }

    public function create()
    {
        return view('admin.pages.ideas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
            'translations.az.title_category_view' => 'required|string',
            'translations.az.title_homepage_tab_view' => 'required|string',
            'translations.en.title_category_view' => 'required|string',
            'translations.en.title_homepage_tab_view' => 'required|string',
            'translations.ru.title_category_view' => 'required|string',
            'translations.ru.title_homepage_tab_view' => 'required|string',
        ]);

        $idea = Idea::create([
            'title_category_view' => "",
            'title_homepage_tab_view' => "",
            'is_active' => $request->input('is_active'),
        ]);

        // Save translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $idea->translations()->create([
                'locale' => $locale,
                'title_category_view' => $request->input("translations.$locale.title_category_view"),
                'title_homepage_tab_view' => $request->input("translations.$locale.title_homepage_tab_view"),
            ]);
        }

        return redirect()->route('admin.ideas.index')->with('success', 'Idea created successfully.');
    }

    public function edit(Idea $idea)
    {
        return view('admin.pages.ideas.edit', compact('idea'));
    }

    public function update(Request $request, Idea $idea)
    {
        $request->validate([
            'is_active' => 'required|boolean',
            'translations.az.title_category_view' => 'required|string',
            'translations.az.title_homepage_tab_view' => 'required|string',
            'translations.en.title_category_view' => 'required|string',
            'translations.en.title_homepage_tab_view' => 'required|string',
            'translations.ru.title_category_view' => 'required|string',
            'translations.ru.title_homepage_tab_view' => 'required|string',
        ]);

        $idea->update($request->only('is_active'));

        // Update translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $translation = $idea->translations()->where('locale', $locale)->first();

            if ($translation) {
                $translation->update([
                    'title_category_view' => $request->input("translations.$locale.title_category_view"),
                    'title_homepage_tab_view' => $request->input("translations.$locale.title_homepage_tab_view"),
                ]);
            } else {
                $idea->translations()->create([
                    'locale' => $locale,
                    'title_category_view' => $request->input("translations.$locale.title_category_view"),
                    'title_homepage_tab_view' => $request->input("translations.$locale.title_homepage_tab_view"),
                ]);
            }
        }

        return redirect()->route('admin.ideas.index')->with('success', 'Idea updated successfully.');
    }

    public function destroy(Idea $idea)
    {
        $idea->delete();
        return redirect()->route('admin.ideas.index')->with('success', 'Idea deleted successfully.');
    }
}
