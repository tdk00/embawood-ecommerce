<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\VideoCallRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiVideoCallRequestController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Create the new video call request
        $videoCallRequest = VideoCallRequest::create([
            'name' => $validatedData['name'],
            'whatsapp_number' => $validatedData['whatsapp_number'],
            'subject' => $validatedData['subject'],
            'address' => $validatedData['address'],
            'status' => 'pending', // Default status
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Video call request created successfully.',
            'data' => $videoCallRequest
        ], Response::HTTP_CREATED);
    }
}
