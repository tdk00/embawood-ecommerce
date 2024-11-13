<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification\Notification;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    protected $pushNotificationService;
    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService = $pushNotificationService;
    }
    public function index()
    {
        $notifications = Notification::orderByDesc('created_at')->get();
        return view('admin.pages.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.pages.notifications.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notification::create($data);
        return redirect()->route('admin.notifications.index')->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        return view('admin.pages.notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification->update($data);
        return redirect()->route('admin.notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully.');
    }

    public function sendNotification(Notification $notification)
    {
        try {
            // Send the push notification to the 'all_app_users' topic
            $this->pushNotificationService->sendPushNotificationToTopic(
                'all_app_users',
                $notification->title,
                $notification->message
            );

            // Update notification status to 'sent' upon success
            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $message = 'Notification sent successfully.';
        } catch (\Exception $e) {
            // Log error if sending fails, and mark notification as 'failed'
            Log::error('Push Notification Failed: ' . $e->getMessage());

            $notification->update(['status' => 'failed']);
            $message = 'Failed to send notification.';
        }

        return redirect()->route('admin.notifications.index')->with('status', $message);
    }
}
