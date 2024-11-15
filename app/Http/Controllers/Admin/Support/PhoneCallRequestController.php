<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\PhoneCallRequest;
use Illuminate\Http\Request;

class PhoneCallRequestController extends Controller
{
    public function index()
    {
        // Fetch paginated phone call requests with user details
        $phoneCallRequests = PhoneCallRequest::with('user')->paginate(10);

        return view('admin.pages.support.phone_call_requests.index', compact('phoneCallRequests'));
    }

    public function update(Request $request, PhoneCallRequest $phoneCallRequest)
    {
        // Validate the status field
        $request->validate([
            'status' => 'required|in:pending,rejected,completed',
        ]);

        // Update the status
        $phoneCallRequest->status = $request->status;
        $phoneCallRequest->save();

        return redirect()->route('admin.phone_call_requests.index')->with('success', 'Status updated successfully.');
    }

    public function destroy(PhoneCallRequest $phoneCallRequest)
    {
        // Delete the phone call request
        $phoneCallRequest->delete();

        return redirect()->route('admin.phone_call_requests.index')->with('success', 'Phone call request deleted successfully.');
    }
}
