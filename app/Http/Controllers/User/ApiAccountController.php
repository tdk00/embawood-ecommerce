<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ApiAccountController extends Controller
{
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
}
