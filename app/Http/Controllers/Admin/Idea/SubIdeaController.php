<?php

namespace App\Http\Controllers\Admin\Idea;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use App\Models\Ideas\SubIdea;
use Illuminate\Http\Request;

class SubIdeaController extends Controller
{
    public function index()
    {
        $subIdeas = SubIdea::with('idea')->get(); // Include the parent Idea for each SubIdea
        return view('admin.pages.sub-ideas.index', compact('subIdeas'));
    }

    public function create(Request $request)
    {
        $ideas = Idea::all(); // Fetch all ideas to associate with SubIdea
        $selectedIdeaId = $request->input('idea_id'); // Get the idea_id from the query string if present
        return view('admin.pages.sub-ideas.create', compact('ideas', 'selectedIdeaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idea_id' => 'required|exists:ideas,id',
            'translations.az.title' => 'required|string',
            'translations.en.title' => 'required|string',
            'translations.ru.title' => 'required|string',
            'image_category_view' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'image_homepage_tab_view' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'is_active' => 'required|boolean',
        ]);

        $subIdea = new SubIdea([
            'idea_id' => $request->input('idea_id'),
            'is_active' => $request->input('is_active'),
            'title' => ""
        ]);

        // Image handling
        if ($request->hasFile('image_category_view')) {
            $file = $request->file('image_category_view');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/ideas', $filename, 'public');
            $subIdea->image_category_view = $filename;
        }

        if ($request->hasFile('image_homepage_tab_view')) {
            $file = $request->file('image_homepage_tab_view');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/ideas', $filename, 'public');
            $subIdea->image_homepage_tab_view = $filename;
        }

        $subIdea->save();

        // Save translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $subIdea->translations()->create([
                'locale' => $locale,
                'title' => $request->input("translations.$locale.title"),
            ]);
        }

        return redirect()->route('admin.sub-ideas.index')->with('success', 'SubIdea created successfully.');
    }

    public function edit(SubIdea $subIdea)
    {
        $ideas = Idea::all(); // Fetch all ideas to associate with SubIdea
        return view('admin.pages.sub-ideas.edit', compact('subIdea', 'ideas'));
    }

    public function update(Request $request, SubIdea $subIdea)
    {
        $request->validate([
            'idea_id' => 'required|exists:ideas,id',
            'translations.az.title' => 'required|string',
            'translations.en.title' => 'required|string',
            'translations.ru.title' => 'required|string',
            'image_category_view' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'image_homepage_tab_view' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'is_active' => 'required|boolean',
        ]);

        $subIdea->update($request->only(['idea_id', 'is_active']));

        // Image handling
        if ($request->hasFile('image_category_view')) {
            $file = $request->file('image_category_view');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/ideas', $filename, 'public');
            $subIdea->image_category_view = $filename;
        }

        if ($request->hasFile('image_homepage_tab_view')) {
            $file = $request->file('image_homepage_tab_view');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/ideas', $filename, 'public');
            $subIdea->image_homepage_tab_view = $filename;
        }

        // Update translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $translation = $subIdea->translations()->where('locale', $locale)->first();
            if ($translation) {
                $translation->update([
                    'title' => $request->input("translations.$locale.title"),
                ]);
            } else {
                $subIdea->translations()->create([
                    'locale' => $locale,
                    'title' => $request->input("translations.$locale.title"),
                ]);
            }
        }
        $subIdea->save();

        return redirect()->route('admin.sub-ideas.index')->with('success', 'SubIdea updated successfully.');
    }

    public function destroy(SubIdea $subIdea)
    {
        $subIdea->delete();
        return redirect()->route('admin.sub-ideas.index')->with('success', 'SubIdea deleted successfully.');
    }

    public function listByIdea(Idea $idea)
    {
        $subIdeas = $idea->subIdeas()->with('translations')->get();
        return view('admin.pages.sub-ideas.list-by-idea', compact('subIdeas', 'idea'));
    }
}
