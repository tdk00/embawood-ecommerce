<?php

namespace App\Http\Controllers\Admin\Idea;

use App\Http\Controllers\Controller;
use App\Models\Ideas\SubIdea;
use App\Models\Ideas\SubIdeaItem;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class SubIdeaItemController extends Controller
{
    public function index()
    {
        $subIdeaItems = SubIdeaItem::with(['subIdea', 'products', 'images'])->get();
        return view('admin.pages.sub-idea-items.index', compact('subIdeaItems'));
    }

    public function create(Request $request)
    {
        $subIdeas = SubIdea::all(); // Get all SubIdeas for selection
        $products = Product::main()->get(); // Get all products for selection
        $selectedSubIdeaId = $request->input('sub_idea_id'); // Pre-select SubIdea if passed
        return view('admin.pages.sub-idea-items.create', compact('subIdeas', 'products', 'selectedSubIdeaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_idea_id' => 'required|exists:sub_ideas,id',
            'translations.az.title' => 'required|string',
            'translations.en.title' => 'required|string',
            'translations.ru.title' => 'required|string',
            'translations.az.description' => 'required|string',
            'translations.en.description' => 'required|string',
            'translations.ru.description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'products' => 'nullable|array',
            'is_active' => 'required|boolean',
        ]);

        $subIdeaItem = new SubIdeaItem($request->only(['sub_idea_id', 'is_active']));
        $subIdeaItem = new SubIdeaItem(
            [
                'sub_idea_id' => $request->input('sub_idea_id'),
                'is_active' => $request->input('is_active'),
                'title' => "",
                'description' => "",
            ]
        );
        $subIdeaItem->save();

        // Handle translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $subIdeaItem->translations()->create([
                'locale' => $locale,
                'title' => $request->input("translations.$locale.title"),
                'description' => $request->input("translations.$locale.description"),
            ]);
        }

        // Handle images (multiple images upload)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('images/ideas', $filename, 'public');
                $subIdeaItem->images()->create(['image_url' => $filename]);
            }
        }

        // Handle product associations (many-to-many)
        if ($request->filled('products')) {
            $subIdeaItem->products()->sync($request->input('products'));
        }

        return redirect()->route('admin.sub-idea-items.index')->with('success', 'SubIdeaItem created successfully.');
    }

    public function edit(SubIdeaItem $subIdeaItem)
    {
        $subIdeas = SubIdea::all();
        $products = Product::main()->get();
        return view('admin.pages.sub-idea-items.edit', compact('subIdeaItem', 'subIdeas', 'products'));
    }

    public function update(Request $request, SubIdeaItem $subIdeaItem)
    {
        $request->validate([
            'sub_idea_id' => 'required|exists:sub_ideas,id',
            'translations.az.title' => 'required|string',
            'translations.en.title' => 'required|string',
            'translations.ru.title' => 'required|string',
            'translations.az.description' => 'required|string',
            'translations.en.description' => 'required|string',
            'translations.ru.description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'products' => 'nullable|array',
            'is_active' => 'required|boolean',
        ]);

        $subIdeaItem->update($request->only(['sub_idea_id', 'is_active']));

        // Update translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $translation = $subIdeaItem->translations()->where('locale', $locale)->first();
            if ($translation) {
                $translation->update([
                    'title' => $request->input("translations.$locale.title"),
                    'description' => $request->input("translations.$locale.description"),
                ]);
            } else {
                $subIdeaItem->translations()->create([
                    'locale' => $locale,
                    'title' => $request->input("translations.$locale.title"),
                    'description' => $request->input("translations.$locale.description"),
                ]);
            }
        }

        // Update images (add new images)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('images/ideas', $filename, 'public');
                $subIdeaItem->images()->create(['image_url' => $filename]);
            }
        }

        // Update product associations (many-to-many)
        if ($request->filled('products')) {
            $subIdeaItem->products()->sync($request->input('products'));
        }

        return redirect()->route('admin.sub-idea-items.index')->with('success', 'SubIdeaItem updated successfully.');
    }

    public function destroy(SubIdeaItem $subIdeaItem)
    {
        $subIdeaItem->delete();
        return redirect()->route('admin.sub-idea-items.index')->with('success', 'SubIdeaItem deleted successfully.');
    }

    public function listBySubIdea(SubIdea $subIdea)
    {
        $subIdeaItems = $subIdea->subIdeaItems()->with(['translations', 'products', 'images'])->get();
        return view('admin.pages.sub-idea-items.list-by-sub-idea', compact('subIdeaItems', 'subIdea'));
    }
}
