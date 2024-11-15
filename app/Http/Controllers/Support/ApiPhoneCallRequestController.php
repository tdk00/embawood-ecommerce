<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\PhoneCallRequest;
use App\Models\Support\VideoCallRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ApiPhoneCallRequestController extends Controller
{
    public function store(Request $request)
    {
        // Get the authenticated user's ID
        $userId = Auth::guard('api')->id();

        // Validate the request input
        $request->validate([
            // No input needed for status; it's set to 'pending' by default
        ]);

        // Create a new phone call request
        $phoneCallRequest = PhoneCallRequest::create([
            'user_id' => $userId,
            'status' => 'pending', // Default status for new requests
        ]);

        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Phone call request created successfully.',
            'data' => $phoneCallRequest,
        ], 201);
    }
}
