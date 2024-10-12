<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Store;
use App\Models\News\News;
use Illuminate\Http\Request;

class ApiStoresController extends Controller
{
    public function index()
    {
        // Fetch all stores with their associated regions and phone numbers
        $stores = Store::with(['region', 'phoneNumbers'])->get();

        // Group the stores by their regions
        $groupedStores = $stores->groupBy(function ($store) {
            return $store->region ? $store->region->id : 'no_region';
        });

        // Prepare regions response with stores grouped under each region
        $regions = $groupedStores->map(function ($stores, $regionId) {
            $region = $stores->first()->region; // Fetch the region from the first store of the group

            return [
                'id' => $regionId == 'no_region' ? null : $region->id,
                'name' => $regionId == 'no_region' ? 'No Region' : $region->name,
                'stores' => $stores->map(function ($store) {
                    return [
                        'id' => $store->id,
                        'name' => $store->name,
                        'address' => $store->address,
                        'city' => $store->city,
                        'latitude' => $store->latitude,
                        'longitude' => $store->longitude,
                        'phone_numbers' => $store->phoneNumbers->map(function ($phoneNumber) {
                            return [
                                'id' => $phoneNumber->id,
                                'store_id' => $phoneNumber->store_id,
                                'phone_number' => $phoneNumber->phone_number
                            ];
                        }),
                    ];
                }),
            ];
        })->values(); // Ensure it's an array of regions without keys

        // Return the regions with associated stores
        return response()->json(['regions' => $regions]);
    }

    public function nearest(Request $request)
    {
        // Validate the request to ensure lat and long are provided
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Calculate the nearest store using the Haversine formula
        $nearestStore = Store::selectRaw(
            "*, ( 6371 * acos( cos( radians(?) ) *
            cos( radians( latitude ) ) *
            cos( radians( longitude ) - radians(?) ) +
            sin( radians(?) ) *
            sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude]
        )->with('phoneNumbers')
            ->orderBy('distance')
            ->first();

        // Return nearest store
        return response()->json($nearestStore);
    }
}
