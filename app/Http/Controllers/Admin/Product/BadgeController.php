<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::paginate(10); // Adjust the pagination as needed
        return view('admin.pages.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin.pages.badges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'badge_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
        ]);

        if ($validated['is_active']) {
            Badge::where('is_active', true)->update(['is_active' => false]);
        }

        // Handle file upload: save only the file name
        if ($request->hasFile('badge_image')) {
            $imageName = $request->file('badge_image')->getClientOriginalName();
            $request->file('badge_image')->storeAs('public/images/badge', $imageName);
            $validated['badge_image'] = $imageName; // Save only the file name
        }

        // Store the badge
        Badge::create($validated);

        return redirect()->route('admin.badges.index')->with('success', 'Badge created successfully.');
    }

    public function edit(Badge $badge)
    {
        return view('admin.pages.badges.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'badge_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
        ]);

        if ($validated['is_active']) {
            Badge::where('is_active', true)->where('id', '!=', $badge->id)->update(['is_active' => false]);
        }

        // Handle file upload: save only the new file name if updated
        if ($request->hasFile('badge_image')) {
            // Delete the old image if a new one is uploaded
            if ($badge->badge_image) {
                Storage::delete('public/images/badge/' . $badge->badge_image);
            }

            $imageName = $request->file('badge_image')->getClientOriginalName();
            $request->file('badge_image')->storeAs('public/images/badge', $imageName);
            $validated['badge_image'] = $imageName; // Save only the file name
        }

        // Update the badge
        $badge->update($validated);

        return redirect()->route('admin.badges.index')->with('success', 'Badge updated successfully.');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return redirect()->route('admin.badges.index')->with('success', 'Badge deleted successfully.');
    }
}
