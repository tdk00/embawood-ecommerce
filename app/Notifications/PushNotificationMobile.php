<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class PushNotificationMobile extends Notification
{
    private $title;
    private $body;

    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        $messages = [];

        // Loop through each FCM token and create a message
        foreach ($notifiable->fcmTokens as $token) {
            $messages[] = FcmMessage::create()
                ->setToken($token->token)
                ->setNotification(
                    FcmNotification::create()
                        ->setTitle($this->title)
                        ->setBody($this->body)
                );
        }

        return $messages;
    }
}
