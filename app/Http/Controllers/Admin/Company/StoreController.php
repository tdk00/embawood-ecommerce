<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Store;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('phoneNumbers')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.company.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.pages.company.stores.create');
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            Store::create($request->all());

            return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');

        } catch (QueryException $e) {
            // Handle SQL error
            return redirect()->back()->withErrors(['db_error' => 'An error occurred while saving the store. Please check your input.']);
        } catch (\Exception $e) {
            // Handle general errors
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred. Please try again.']);
        }

    }

    public function edit(Store $store)
    {
        return view('admin.pages.company.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $store->update($request->all());

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }
}
