<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ApiAccountController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/user/account",
     *     operationId="getUserAccountDetails",
     *     tags={"Account"},
     *     summary="Retrieve account details for the authenticated user",
     *     description="Returns the account details of the authenticated user, including non-changeable and changeable fields.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Account details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's first name", example="John"),
     *             @OA\Property(property="surname", type="string", nullable=true, description="User's last name", example="Doe"),
     *             @OA\Property(property="phone", type="string", description="User's phone number (non-changeable)", example="+123456789"),
     *             @OA\Property(property="gender", type="string", description="User's gender", example="male"),
     *             @OA\Property(property="birthdate", type="string", format="date", nullable=true, description="User's birthdate in ISO 8601 format", example="1990-05-20T00:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="User not found")
     *         )
     *     )
     * )
     */
    public function getDetails()
    {
        // Get the authenticated user via the API guard
        $user = Auth::guard('api')?->user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Return user details in a structure compatible with Flutter model
        return response()->json([
            'name' => $user->name,                   // Changeable
            'surname' => $user->surname,             // Changeable
            'phone' => $user->phone,                 // Non-changeable
            'gender' => $user->gender,               // Changeable
            'birthdate' => $user->birthdate ? $user->birthdate->toIso8601String() : null, // Ensure ISO 8601 format
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/user/account",
     *     operationId="updateUserAccountDetails",
     *     tags={"Account"},
     *     summary="Update account details for the authenticated user",
     *     description="Allows the authenticated user to update their account details. The phone number is non-changeable.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's first name", example="John"),
     *             @OA\Property(property="surname", type="string", nullable=true, description="User's last name", example="Doe"),
     *             @OA\Property(property="gender", type="string", description="User's gender", enum={"male", "female", "other"}, example="male"),
     *             @OA\Property(property="birthdate", type="string", format="date", description="User's birthdate in ISO 8601 format", example="1990-05-20T00:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User account updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Success message", example="User updated successfully"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 description="Updated user account details",
     *                 @OA\Property(property="id", type="integer", description="User ID", example=1),
     *                 @OA\Property(property="name", type="string", description="User's first name", example="John"),
     *                 @OA\Property(property="surname", type="string", nullable=true, description="User's last name", example="Doe"),
     *                 @OA\Property(property="phone", type="string", description="User's phone number (non-changeable)", example="+123456789"),
     *                 @OA\Property(property="gender", type="string", description="User's gender", example="male"),
     *                 @OA\Property(property="birthdate", type="string", format="date", description="User's birthdate in ISO 8601 format", example="1990-05-20T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="User not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Validation error message", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Details of the validation errors",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     *                 @OA\Property(property="gender", type="array", @OA\Items(type="string", example="The selected gender is invalid.")),
     *                 @OA\Property(property="birthdate", type="array", @OA\Items(type="string", example="The birthdate must be a date before today."))
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        // Get the authenticated user via the API guard
        $user = Auth::guard('api')?->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate the request input (without 'phone' since it's non-changeable)
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date|before:today',
        ]);

        // Update the allowed fields (name, surname, gender, birthdate)
        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
        ]);

        // Return success response with updated user data
        return response()->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'phone' => $user->phone,               // Return phone as non-changeable
                'gender' => $user->gender,
                'birthdate' => $user->birthdate->toIso8601String(),
            ]
        ]);
    }
}
