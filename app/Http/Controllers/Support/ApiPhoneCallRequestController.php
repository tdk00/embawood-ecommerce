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
    /**
     * @OA\Post(
     *     path="/api/phone_call_requests",
     *     operationId="createPhoneCallRequest",
     *     tags={"Phone Call Requests"},
     *     summary="Create a new phone call request",
     *     description="Allows the authenticated user to create a new phone call request. The request will be created with a default status of 'pending'.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         description="No additional fields are required for this request."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Phone call request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=true),
     *             @OA\Property(property="message", type="string", description="Success message", example="Phone call request created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Created phone call request details",
     *                 @OA\Property(property="id", type="integer", description="Phone call request ID", example=1),
     *                 @OA\Property(property="user_id", type="integer", description="ID of the user who made the request", example=10),
     *                 @OA\Property(property="status", type="string", description="Status of the request", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", description="Timestamp when the request was created", example="2024-11-20T15:30:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime", description="Timestamp when the request was last updated", example="2024-11-20T15:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="User not authenticated.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $userId = Auth::guard('api')->id();

        $request->validate([
        ]);

        // Create a new phone call request
        $phoneCallRequest = PhoneCallRequest::create([
            'user_id' => $userId,
            'status' => 'pending',
        ]);

        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Phone call request created successfully.',
            'data' => $phoneCallRequest,
        ], 201);
    }
}
