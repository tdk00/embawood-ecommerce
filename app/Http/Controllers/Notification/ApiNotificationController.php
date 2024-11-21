<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification\Notification;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/notifications",
     *     operationId="getUserNotifications",
     *     tags={"Notifications"},
     *     summary="Retrieve notifications for the authenticated user",
     *     description="Returns both general notifications (applicable to all users) and user-specific notifications for the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Notifications retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="success"),
     *             @OA\Property(
     *                 property="notifications",
     *                 type="array",
     *                 description="List of notifications",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Notification ID", example=1),
     *                     @OA\Property(property="title", type="string", description="Title of the notification", example="Welcome to the app!"),
     *                     @OA\Property(property="message", type="string", description="Notification message", example="Don't forget to check our latest features."),
     *                     @OA\Property(property="status", type="string", description="Notification status", example="unread"),
     *                     @OA\Property(property="sent_at", type="string", format="datetime", description="Date and time when the notification was sent", example="2024-11-20T14:35:00Z"),
     *                     @OA\Property(property="user_id", type="integer", nullable=true, description="User ID if the notification is user-specific, null for general notifications", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Operation status", example="error"),
     *             @OA\Property(property="message", type="string", description="Error message", example="User not authenticated")
     *         )
     *     )
     * )
     */
    public function getUserNotifications(Request $request)
    {
        $userId = $request->user()->id;

        $notifications = Notification::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->orderBy('sent_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
        ]);
    }
}
