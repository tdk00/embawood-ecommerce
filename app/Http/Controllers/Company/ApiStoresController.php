<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Store;
use App\Models\News\News;
use Illuminate\Http\Request;

class ApiStoresController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/company/stores",
     *     operationId="getAllStores",
     *     tags={"Stores"},
     *     summary="Get all stores grouped by region",
     *     description="Retrieves all stores grouped by their respective regions, including store details and associated phone numbers.",
     *     @OA\Response(
     *         response=200,
     *         description="Stores retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="regions",
     *                 type="array",
     *                 description="List of regions with their stores",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", nullable=true, description="Region ID", example=1),
     *                     @OA\Property(property="name", type="string", description="Region name", example="Downtown"),
     *                     @OA\Property(
     *                         property="stores",
     *                         type="array",
     *                         description="List of stores in the region",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", description="Store ID", example=1),
     *                             @OA\Property(property="name", type="string", description="Store name", example="Main Street Store"),
     *                             @OA\Property(property="address", type="string", description="Store address", example="123 Main St"),
     *                             @OA\Property(property="city", type="string", description="City of the store", example="Springfield"),
     *                             @OA\Property(property="latitude", type="number", format="float", description="Latitude of the store", example=40.7128),
     *                             @OA\Property(property="longitude", type="number", format="float", description="Longitude of the store", example=-74.0060),
     *                             @OA\Property(
     *                                 property="phone_numbers",
     *                                 type="array",
     *                                 description="Phone numbers of the store",
     *                                 @OA\Items(
     *                                     @OA\Property(property="id", type="integer", description="Phone number ID", example=1),
     *                                     @OA\Property(property="store_id", type="integer", description="Store ID", example=1),
     *                                     @OA\Property(property="phone_number", type="string", description="Phone number", example="(123) 456-7890")
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $stores = Store::with(['region', 'phoneNumbers'])->get();

        $groupedStores = $stores->groupBy(function ($store) {
            return $store->region ? $store->region->id : 'no_region';
        });

        $regions = $groupedStores->map(function ($stores, $regionId) {
            $region = $stores->first()->region;

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
        })->values();


        return response()->json(['regions' => $regions]);
    }

    /**
     * @OA\Get(
     *     path="/api/company/stores/nearest",
     *     operationId="getNearestStore",
     *     tags={"Stores"},
     *     summary="Get the nearest store",
     *     description="Finds the nearest store to the provided latitude and longitude coordinates.",
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         description="Latitude of the user's location",
     *         @OA\Schema(type="number", format="float", example=40.7128)
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         description="Longitude of the user's location",
     *         @OA\Schema(type="number", format="float", example=-74.0060)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Nearest store retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="Store ID", example=1),
     *             @OA\Property(property="name", type="string", description="Store name", example="Main Street Store"),
     *             @OA\Property(property="address", type="string", description="Store address", example="123 Main St"),
     *             @OA\Property(property="city", type="string", description="City of the store", example="Springfield"),
     *             @OA\Property(property="latitude", type="number", format="float", description="Latitude of the store", example=40.7128),
     *             @OA\Property(property="longitude", type="number", format="float", description="Longitude of the store", example=-74.0060),
     *             @OA\Property(
     *                 property="phone_numbers",
     *                 type="array",
     *                 description="Phone numbers of the store",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Phone number ID", example=1),
     *                     @OA\Property(property="store_id", type="integer", description="Store ID", example=1),
     *                     @OA\Property(property="phone_number", type="string", description="Phone number", example="(123) 456-7890")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", description="Error message", example="Validation failed")
     *         )
     *     )
     * )
     */
    public function nearest(Request $request)
    {

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

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
