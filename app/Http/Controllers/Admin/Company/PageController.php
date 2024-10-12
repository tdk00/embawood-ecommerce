<?php
namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('translations')->get();
        return view('admin.pages.company.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.company.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'show_in_footer' => 'nullable|boolean',
            'title_az' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'required|string',
            'content_az' => 'required|string',
            'content_en' => 'required|string',
            'content_ru' => 'required|string',
        ]);

        $page = Page::create([
            'show_in_footer' => $validated['show_in_footer'] ?? false,
            'title' => $validated['title_az'],
            'content' => $validated['content_az'],
        ]);

        // Store translations
        $page->translations()->createMany([
            [
                'locale' => 'az',
                'title' => $validated['title_az'],
                'content' => $validated['content_az'],
            ],
            [
                'locale' => 'en',
                'title' => $validated['title_en'],
                'content' => $validated['content_en'],
            ],
            [
                'locale' => 'ru',
                'title' => $validated['title_ru'],
                'content' => $validated['content_ru'],
            ]
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.company.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'show_in_footer' => 'nullable|boolean',
            'title_az' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'required|string',
            'content_az' => 'required|string',
            'content_en' => 'required|string',
            'content_ru' => 'required|string',
        ]);

        $page->update([
            'show_in_footer' => $validated['show_in_footer'] ?? false,
        ]);


        foreach (['az', 'en', 'ru'] as $locale) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                ['title' => $validated["title_{$locale}"], 'content' => $validated["content_{$locale}"]]
            );
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully');
    }
}
