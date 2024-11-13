<?php
namespace App\Http\Controllers\User;

use App\Models\User\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function storeFcmToken(Request $request)
    {
        return response()->json(['message' => 'FCM token saved successfully'], 201);
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::guard('api')->user();

        // Check if the FCM token already exists in the database
        $existingToken = FcmToken::where('fcm_token', $request->fcm_token)->first();

        if (!$existingToken) {
            // If the token does not exist, create a new one for the user
            $user->fcmTokens()->create([
                'fcm_token' => $request->fcm_token,
            ]);
            return response()->json(['message' => 'FCM token saved successfully'], 201);
        }

        // If the token already exists, respond accordingly
        return response()->json(['message' => 'FCM token already exists'], 200);
    }
}
