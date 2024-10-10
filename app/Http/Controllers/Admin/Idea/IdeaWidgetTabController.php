<?php

namespace App\Http\Controllers\Admin\Idea;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use App\Models\Ideas\IdeaWidgetTab;
use Illuminate\Http\Request;

class IdeaWidgetTabController extends Controller
{
    public function index()
    {
        $ideaWidgetTabs  = IdeaWidgetTab::with('idea')->orderBy('sort_order')->get();
        return view('admin.pages.idea-widget-tabs.index', compact('ideaWidgetTabs'));
    }

    public function create()
    {
        $ideas = Idea::all(); // Fetch ideas to populate the dropdown
        return view('admin.pages.idea-widget-tabs.create', compact('ideas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idea_id' => 'required|exists:ideas,id',
            'sort_order' => 'required|integer',
        ]);

        IdeaWidgetTab::create($request->all());

        return redirect()->route('admin.idea-widget-tabs.index')
            ->with('success', 'Tab created successfully.');
    }

    public function edit(IdeaWidgetTab $ideaWidgetTab)
    {
        $ideas = Idea::all();
        return view('admin.pages.idea-widget-tabs.edit', compact('ideaWidgetTab', 'ideas'));
    }

    public function update(Request $request, IdeaWidgetTab $ideaWidgetTab)
    {
        $request->validate([
            'idea_id' => 'required|exists:ideas,id',
            'sort_order' => 'required|integer',
        ]);

        $ideaWidgetTab->update($request->all());

        return redirect()->route('admin.idea-widget-tabs.index')
            ->with('success', 'Tab updated successfully.');
    }

    public function destroy(IdeaWidgetTab $ideaWidgetTab)
    {
        $ideaWidgetTab->delete();

        return redirect()->route('admin.idea-widget-tabs.index')
            ->with('success', 'Tab deleted successfully.');
    }
}
