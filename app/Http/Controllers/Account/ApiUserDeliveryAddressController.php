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
     *     tags={"Delivery Addresses"},
     *     summary="Retrieve all delivery addresses for the authenticated user",
     *     description="Returns a list of all delivery addresses associated with the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of delivery addresses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Address ID", example=1),
     *                 @OA\Property(property="fullname", type="string", description="Full name associated with the address", example="John Doe"),
     *                 @OA\Property(property="phone", type="string", description="Phone number", example="+123456789"),
     *                 @OA\Property(property="address_line1", type="string", description="Primary address line", example="123 Main Street"),
     *                 @OA\Property(property="address_line2", type="string", nullable=true, description="Secondary address line", example="Apt 4B"),
     *                 @OA\Property(property="city", type="string", description="City", example="New York"),
     *                 @OA\Property(property="is_default", type="boolean", description="Indicates if this is the default address", example=true)
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
     *     operationId="storeUserDeliveryAddress",
     *     tags={"Delivery Addresses"},
     *     summary="Create a new delivery address",
     *     description="Allows the authenticated user to create a new delivery address. If `is_default` is true, this address will be set as the default.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fullname", type="string", description="Full name", example="Jane Doe"),
     *             @OA\Property(property="phone", type="string", description="Phone number", example="+123456789"),
     *             @OA\Property(property="address_line1", type="string", description="Primary address line", example="456 Elm Street"),
     *             @OA\Property(property="address_line2", type="string", nullable=true, description="Secondary address line", example="Suite 101"),
     *             @OA\Property(property="city", type="string", description="City", example="Los Angeles"),
     *             @OA\Property(property="is_default", type="boolean", nullable=true, description="Indicates if this should be the default address", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Address ID", example=2),
     *             @OA\Property(property="fullname", type="string", description="Full name", example="Jane Doe"),
     *             @OA\Property(property="phone", type="string", description="Phone number", example="+123456789"),
     *             @OA\Property(property="address_line1", type="string", description="Primary address line", example="456 Elm Street"),
     *             @OA\Property(property="address_line2", type="string", nullable=true, description="Secondary address line", example="Suite 101"),
     *             @OA\Property(property="city", type="string", description="City", example="Los Angeles"),
     *             @OA\Property(property="is_default", type="boolean", description="Indicates if this is the default address", example=true)
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
     *     tags={"Delivery Addresses"},
     *     summary="Update an existing delivery address",
     *     description="Allows the authenticated user to update an existing delivery address. If `is_default` is true, this address will be set as the default.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the delivery address to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fullname", type="string", description="Full name", example="Jane Doe"),
     *             @OA\Property(property="phone", type="string", description="Phone number", example="+123456789"),
     *             @OA\Property(property="address_line1", type="string", description="Primary address line", example="789 Maple Ave"),
     *             @OA\Property(property="address_line2", type="string", nullable=true, description="Secondary address line", example="Suite 303"),
     *             @OA\Property(property="city", type="string", description="City", example="San Francisco"),
     *             @OA\Property(property="is_default", type="boolean", nullable=true, description="Indicates if this should be the default address", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Address ID", example=1),
     *             @OA\Property(property="fullname", type="string", description="Full name", example="Jane Doe"),
     *             @OA\Property(property="phone", type="string", description="Phone number", example="+123456789"),
     *             @OA\Property(property="address_line1", type="string", description="Primary address line", example="789 Maple Ave"),
     *             @OA\Property(property="address_line2", type="string", nullable=true, description="Secondary address line", example="Suite 303"),
     *             @OA\Property(property="city", type="string", description="City", example="San Francisco"),
     *             @OA\Property(property="is_default", type="boolean", description="Indicates if this is the default address", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Address not found")
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
     *     tags={"Delivery Addresses"},
     *     summary="Delete a delivery address",
     *     description="Allows the authenticated user to delete an existing delivery address.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the delivery address to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Success message", example="Address deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Address not found")
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
     *     path="/api/user/delivery-addresses/select",
     *     operationId="makeAddressDefault",
     *     tags={"Delivery Addresses"},
     *     summary="Mark a delivery address as default",
     *     description="Allows the authenticated user to mark a specific delivery address as the default.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="ID of the delivery address to mark as default", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address marked as default successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Success message", example="Address selected as default"),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 description="Updated default address",
     *                 @OA\Property(property="id", type="integer", description="Address ID", example=1),
     *                 @OA\Property(property="fullname", type="string", description="Full name", example="Jane Doe"),
     *                 @OA\Property(property="phone", type="string", description="Phone number", example="+123456789"),
     *                 @OA\Property(property="address_line1", type="string", description="Primary address line", example="123 Main St"),
     *                 @OA\Property(property="address_line2", type="string", nullable=true, description="Secondary address line", example="Suite 101"),
     *                 @OA\Property(property="city", type="string", description="City", example="New York"),
     *                 @OA\Property(property="is_default", type="boolean", description="Indicates if this is the default address", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Address not found")
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
