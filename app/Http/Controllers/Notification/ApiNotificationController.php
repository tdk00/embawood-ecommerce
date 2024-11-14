<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification\Notification;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    /**
     * Get notifications for a specific user, including general and user-specific notifications.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotifications(Request $request)
    {
        // Assume the user is authenticated, and we retrieve the user ID from the request or auth.
        $userId = $request->user()->id;

        // Query to get both general (user_id is null) and user-specific notifications.
        $notifications = Notification::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->orderBy('sent_at', 'desc') // Optionally order by sent date
            ->get();

        // Return the notifications as a JSON response
        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
        ]);
    }
}
