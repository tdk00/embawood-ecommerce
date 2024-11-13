<?php

namespace App\Services;

use App\Models\User\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class PushNotificationService
{
    /**
     * Send a push notification to a user.
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @return void
     */
    public function sendPushNotification(User $user, string $title, string $body): void
    {
        // Iterate through each token and send the notification
        foreach ($user->fcmTokens as $token) {
            // Create the FCM Notification with title and body
            $notification = Notification::create($title, $body);

            // Create the CloudMessage with token and notification
            $fcmMessage = CloudMessage::withTarget('token', $token->fcm_token)
                ->withNotification($notification);

            // Send the message through Firebase
            Firebase::messaging()->send($fcmMessage);
        }
    }
}
