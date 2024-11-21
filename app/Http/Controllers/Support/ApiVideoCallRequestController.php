<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\VideoCallRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiVideoCallRequestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/video_call_requests",
     *     operationId="storeVideoCallRequest",
     *     tags={"Video Call Requests"},
     *     summary="Create a new video call request",
     *     description="Submits a request for a video call, including name, WhatsApp number, subject, and address.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "whatsapp_number", "subject", "address"},
     *             @OA\Property(property="name", type="string", description="Name of the requester", example="John Doe"),
     *             @OA\Property(property="whatsapp_number", type="string", description="WhatsApp number of the requester", example="+1234567890"),
     *             @OA\Property(property="subject", type="string", description="Subject of the video call request", example="Product Inquiry"),
     *             @OA\Property(property="address", type="string", description="Address of the requester", example="123 Main Street, Springfield"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Video call request created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Success message", example="Video call request created successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", description="ID of the video call request", example=1),
     *                 @OA\Property(property="name", type="string", description="Name of the requester", example="John Doe"),
     *                 @OA\Property(property="whatsapp_number", type="string", description="WhatsApp number of the requester", example="+1234567890"),
     *                 @OA\Property(property="subject", type="string", description="Subject of the request", example="Product Inquiry"),
     *                 @OA\Property(property="address", type="string", description="Requester's address", example="123 Main Street, Springfield"),
     *                 @OA\Property(property="status", type="string", description="Status of the request", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Time when the request was created", example="2024-11-20T10:15:30Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Time when the request was last updated", example="2024-11-20T10:15:30Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Validation error message", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties=@OA\Property(type="array", @OA\Items(type="string")))
     *         )
     *     )
     * )
     */
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
