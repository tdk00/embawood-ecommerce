<?php

namespace App\Http\Controllers\Admin\Idea;

use App\Http\Controllers\Controller;
use App\Models\Ideas\IdeaWidgetItem;
use App\Models\Ideas\IdeaWidgetTab;
use App\Models\Ideas\SubIdea;
use Illuminate\Http\Request;

class IdeaWidgetItemController extends Controller
{
    public function index($tab_id)
    {
        // Get the parent tab
        $ideaWidgetTab = IdeaWidgetTab::findOrFail($tab_id);

        // Get all items related to the tab
        $ideaWidgetItems = IdeaWidgetItem::where('idea_widget_tab_id', $tab_id)->with('subIdea')->get();

        // Return the view
        return view('admin.pages.idea-widget-items.index', compact('ideaWidgetTab', 'ideaWidgetItems'));
    }

    // Show form to create a new item for a specific tab
    public function create($tab_id)
    {
        // Get the parent tab
        $ideaWidgetTab = IdeaWidgetTab::findOrFail($tab_id);

        // Get all sub ideas to populate the dropdown
        $subIdeas = $ideaWidgetTab->idea->subIdeas;

        // Return the view
        return view('admin.pages.idea-widget-items.create', compact('ideaWidgetTab', 'subIdeas'));
    }

    // Show form to edit an item under a specific tab
    public function edit($tab_id, $id)
    {
        // Get the parent tab
        $ideaWidgetTab = IdeaWidgetTab::findOrFail($tab_id);

        // Get the item
        $ideaWidgetItem = IdeaWidgetItem::findOrFail($id);

        // Get all sub ideas for the dropdown
        $subIdeas = $ideaWidgetTab->idea->subIdeas;

        // Return the view
        return view('admin.pages.idea-widget-items.edit', compact('ideaWidgetTab', 'ideaWidgetItem', 'subIdeas'));
    }

    // Store new item
    public function store(Request $request, $tab_id)
    {
        $request->validate([
            'sub_idea_id' => 'required',
            'sort_order' => 'required|integer',
        ]);

        // Create a new IdeaWidgetItem
        IdeaWidgetItem::create([
            'idea_widget_tab_id' => $tab_id,
            'sub_idea_id' => $request->sub_idea_id,
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->route('admin.idea-widget-items.index', $tab_id)->with('success', 'Item created successfully');
    }

    // Update existing item
    public function update(Request $request, $tab_id, $id)
    {
        $request->validate([
            'sub_idea_id' => 'required',
            'sort_order' => 'required|integer',
        ]);

        // Find and update the IdeaWidgetItem
        $ideaWidgetItem = IdeaWidgetItem::findOrFail($id);
        $ideaWidgetItem->update([
            'sub_idea_id' => $request->sub_idea_id,
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->route('admin.idea-widget-items.index', $tab_id)->with('success', 'Item updated successfully');
    }
}
