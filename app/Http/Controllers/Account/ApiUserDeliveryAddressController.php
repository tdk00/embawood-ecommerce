<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserDeliveryAddressController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->user = Auth::guard('sanctum')->user();
    }

    /**
     * @OA\Get(
     *     path="/api/user/delivery-addresses",
     *     operationId="getUserDeliveryAddresses",
     *     tags={"User Delivery Addresses"},
     *     summary="Get all delivery addresses for the authenticated user",
     *     description="Returns a list of all delivery addresses associated with the authenticated user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of delivery addresses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="fullname", type="string", example="John Doe"),
     *                 @OA\Property(property="phone", type="string", example="+1234567890"),
     *                 @OA\Property(property="address_line1", type="string", example="123 Main St"),
     *                 @OA\Property(property="address_line2", type="string", example="Apartment 4B"),
     *                 @OA\Property(property="city", type="string", example="New York"),
     *                 @OA\Property(property="is_default", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $addresses = $this->user->deliveryAddresses;
        $transformedAddresses = $addresses->map(function ($address) {
            return [
                'id' => $address->id,
                'fullname' => $address->fullname,
                'phone' => $address->phone,
                'address_line1' => $address->address_line1,
                'address_line2' => $address->address_line2,
                'city' => $address->city,
                'is_default' => $address->is_default,
            ];
        });
        return response()->json($transformedAddresses);
    }

    /**
     * @OA\Post(
     *     path="/api/user/delivery-addresses",
     *     operationId="createUserDeliveryAddress",
     *     tags={"User Delivery Addresses"},
     *     summary="Create a new delivery address for the authenticated user",
     *     description="Creates a new delivery address and returns the created address data.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fullname", type="string", example="John Doe"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="address_line1", type="string", example="123 Main St"),
     *             @OA\Property(property="address_line2", type="string", example="Apartment 4B", nullable=true),
     *             @OA\Property(property="city", type="string", example="New York"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="fullname", type="string", example="John Doe"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="address_line1", type="string", example="123 Main St"),
     *             @OA\Property(property="address_line2", type="string", example="Apartment 4B"),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="is_default", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $is_default = 0;
        if ($request->is_default) {
            $this->user->deliveryAddresses()->update(['is_default' => 0]);
            $is_default = 1;
        }

        // Create the new address
        $address = $this->user->deliveryAddresses()->create([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            // Set the new address as default
            'is_default' => $is_default,
        ]);

        return response()->json($address, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/user/delivery-addresses/{id}",
     *     operationId="updateUserDeliveryAddress",
     *     tags={"User Delivery Addresses"},
     *     summary="Update a delivery address for the authenticated user",
     *     description="Updates an existing delivery address and returns the updated address data.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the delivery address to update",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fullname", type="string", example="John Doe"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="address_line1", type="string", example="123 Main St"),
     *             @OA\Property(property="address_line2", type="string", example="Apartment 4B", nullable=true),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="is_default", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="fullname", type="string", example="John Doe"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="address_line1", type="string", example="123 Main St"),
     *             @OA\Property(property="address_line2", type="string", example="Apartment 4B"),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="is_default", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Address not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Find the user's delivery address by user ID and address ID
        $address = UserDeliveryAddress::where('user_id', $this->user->id)->find($id);

        // Return error response if the address is not found
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }


        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'is_default' => 'boolean',
        ]);

        // Update the address without using $request->all() to handle the is_default field manually
        $address->update([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
        ]);

        // If the updated address is marked as the default, update other addresses
        if ($request->has('is_default') && $request->is_default) {
            // Set all other addresses to not be default
            $this->user->deliveryAddresses()->update(['is_default' => 0]);

            // Mark this address as the default
            $address->is_default = 1;
            $address->save();
        }

        return response()->json($address);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/delivery-addresses/{id}",
     *     operationId="deleteUserDeliveryAddress",
     *     tags={"User Delivery Addresses"},
     *     summary="Delete a delivery address for the authenticated user",
     *     description="Deletes an existing delivery address.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the delivery address to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Address deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Address not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $address = UserDeliveryAddress::where('user_id', $this->user->id)->find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();
        return response()->json(['message' => 'Address deleted']);
    }

    /**
     * @OA\Post(
     *     path="/api/user/delivery-addresses/{id}/select",
     *     operationId="selectUserDeliveryAddress",
     *     tags={"User Delivery Addresses"},
     *     summary="Set a delivery address as the default for the authenticated user",
     *     description="Sets the selected delivery address as the default address for the user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         description="Locale for translations",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="en"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the delivery address to select as default",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address selected as default"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Address not found")
     *         )
     *     )
     * )
     */
    public function makeSelected(Request $request)
    {
        // Validate that the 'id' is provided and is an integer
        $request->validate([
            'id' => 'required|integer',
        ]);

        // Find the user's delivery address by user ID and address ID
        $address = UserDeliveryAddress::where('user_id', $this->user->id)->find($request->id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        UserDeliveryAddress::where('user_id', $this->user->id)->whereNot('id', $request->id)->update(['is_default' => 0]);

        // Set the selected address as the default
        $address->is_default = 1;
        $address->save();

        // Return a success response with the updated address
        return response()->json(['message' => 'Address selected as default', 'address' => $address], 200);
    }
}
