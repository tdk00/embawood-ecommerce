<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.company.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.pages.company.regions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Region::create($request->all());

        return redirect()->route('admin.regions.index')->with('success', 'Region created successfully.');
    }

    public function edit(Region $region)
    {
        return view('admin.pages.company.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $region->update($request->all());

        return redirect()->route('admin.regions.index')->with('success', 'Region updated successfully.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Region deleted successfully.');
    }
}
