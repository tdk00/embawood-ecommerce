<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialMediaController extends Controller
{
    // List of allowed types (replace with a constant in the model if you want)
    protected const TYPES = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok', 'other'];

    public function index()
    {
        $socialMediaLinks = SocialMedia::all();
        return view('admin.pages.company.social_media.index', compact('socialMediaLinks'));
    }

    public function create()
    {
        // Pass allowed types to the view
        return view('admin.pages.company.social_media.create', ['types' => self::TYPES]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'svg_icon' => 'required|file|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'url' => 'required|url',
            'type' => 'required|in:' . implode(',', self::TYPES),
            'is_active' => 'required|boolean',
        ]);

        $data = $request->except('svg_icon');

        // Handle the SVG icon upload
        if ($request->hasFile('svg_icon')) {
            $file = $request->file('svg_icon');
            $svgIconName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/social_media_icons', $svgIconName, 'public');
            $data['svg_icon'] = $svgIconName;
        }

        SocialMedia::create($data);

        return redirect()->route('admin.social_media.index')->with('success', 'Sosial media linki uğurla yaradıldı.');
    }

    public function edit(SocialMedia $socialMedia)
    {
        $types = SocialMedia::TYPES;
        return view('admin.pages.company.social_media.edit', compact('socialMedia', 'types'));
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        $request->validate([
            'svg_icon' => 'nullable|file|mimes:jpg,jpeg,png,bmp,webp,svg|max:10240',
            'url' => 'required|url',
            'type' => 'required|in:' . implode(',', self::TYPES),
            'is_active' => 'required|boolean',
        ]);

        $data = $request->except('svg_icon');

        // Handle the SVG icon upload
        if ($request->hasFile('svg_icon')) {
            // Delete the old icon if it exists
            if ($socialMedia->svg_icon && Storage::disk('public')->exists('images/social_media_icons/' . $socialMedia->svg_icon)) {
                Storage::disk('public')->delete('images/social_media_icons/' . $socialMedia->svg_icon);
            }

            $file = $request->file('svg_icon');
            $svgIconName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images/social_media_icons', $svgIconName, 'public');
            $data['svg_icon'] = $svgIconName;
        }

        $socialMedia->update($data);

        return redirect()->route('admin.social_media.index')->with('success', 'Sosial media linki uğurla yeniləndi.');
    }

    public function destroy(SocialMedia $socialMedia)
    {
        if ($socialMedia->svg_icon && Storage::disk('public')->exists('images/social_media_icons/' . $socialMedia->svg_icon)) {
            Storage::disk('public')->delete('images/social_media_icons/' . $socialMedia->svg_icon);
        }

        $socialMedia->delete();
        return redirect()->route('admin.social_media.index')->with('success', 'Sosial media linki uğurla silindi.');
    }
}
