<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Store;
use App\Models\Company\StorePhoneNumber;
use Illuminate\Http\Request;

class StorePhoneNumberController extends Controller
{
    public function index()
    {
        $phoneNumbers = StorePhoneNumber::with('store')->paginate(10);
        return view('admin.pages.company.store_phone_numbers.index', compact('phoneNumbers'));
    }

    public function create()
    {
        $stores = Store::all();
        return view('admin.pages.company.store_phone_numbers.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'phone_number' => 'required|string|max:35',
        ]);

        StorePhoneNumber::create($request->all());

        return redirect()->route('admin.store-phone-numbers.index')->with('success', 'Phone number added successfully.');
    }

    public function edit(StorePhoneNumber $storePhoneNumber)
    {
        $stores = Store::all();
        return view('admin.pages.company.store_phone_numbers.edit', compact('storePhoneNumber', 'stores'));
    }

    public function update(Request $request, StorePhoneNumber $storePhoneNumber)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'phone_number' => 'required|string|max:35',
        ]);

        $storePhoneNumber->update($request->all());

        return redirect()->route('admin.store-phone-numbers.index')->with('success', 'Phone number updated successfully.');
    }

    public function destroy(StorePhoneNumber $storePhoneNumber)
    {
        $storePhoneNumber->delete();
        return redirect()->route('admin.store-phone-numbers.index')->with('success', 'Phone number deleted successfully.');
    }
}
