<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\VideoCallRequest;
use Illuminate\Http\Request;

class VideoCallRequestController extends Controller
{
    public function index()
    {
        // Fetch paginated video call requests
        $videoCallRequests = VideoCallRequest::paginate(10);

        return view('admin.pages.support.video_call_requests.index', compact('videoCallRequests'));
    }

    public function update(Request $request, VideoCallRequest $videoCallRequest)
    {
        // Validate the status field to ensure it's either pending, rejected, or completed
        $request->validate([
            'status' => 'required|in:pending,rejected,completed',
        ]);

        // Update the status
        $videoCallRequest->status = $request->status;
        $videoCallRequest->save();

        return redirect()->route('admin.video_call_requests.index')->with('success', 'Status updated successfully.');
    }

    public function destroy(VideoCallRequest $videoCallRequest)
    {
        // Delete the video call request
        $videoCallRequest->delete();

        return redirect()->route('admin.video_call_requests.index')->with('success', 'Video call request deleted successfully.');
    }
}
