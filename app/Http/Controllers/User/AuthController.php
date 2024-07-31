<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Services\Bonus\BonusService;
use App\Services\User\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $otpService;
    protected $bonusService;

    public function __construct(OtpService $otpService, BonusService $bonusService)
    {
        $this->otpService = $otpService;
        $this->bonusService = $bonusService;

    }

    /**
     * @OA\Post(
     *     path="/api/authenticate",
     *     tags={"Auth"},
     *     summary="Authenticate user",
     *     description="Authenticate user with phone number",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="0991111111")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="otp_required"),
     *             @OA\Property(property="message", type="string", example="OTP sent to your phone number"),
     *             @OA\Property(property="otp", type="string", example="123456")  // Note: Remove OTP from response in production
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            if ($user->phone_verified_at) {
                if ($user->password) {
                    return response()->json(['status' => 'password_required', 'message' => 'Password is required to login.']);
                } else {
                    $otp = $this->otpService->generateOtp($request->phone);
                    return response()->json([
                        'status' => 'otp_required',
                        'message' => 'OTP sent to your phone number',
                        'otp' => $otp
                    ]);
                }
            } else {
                $otp = $this->otpService->generateOtp($request->phone);
                return response()->json([
                    'status' => 'otp_required',
                    'message' => 'OTP sent to your phone number',
                    'otp' => $otp
                ]);
            }
        } else {
            $user = User::create(['phone' => $request->phone]);
            $this->bonusService->awardRegistrationBonus( $user );
            $otp = $this->otpService->generateOtp($request->phone);
            return response()->json([
                'status' => 'otp_required',
                'message' => 'OTP sent to your phone number',
                'otp' => $otp
            ]);

        }
    }

    /**
     * @OA\Post(
     *     path="/api/verify-otp",
     *     tags={"Auth"},
     *     summary="Verify OTP",
     *     description="Verify OTP sent to user's phone",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="0991111111"),
     *             @OA\Property(property="otp", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verification response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="set_password"),
     *             @OA\Property(property="message", type="string", example="OTP verified, set your password."),
     *             @OA\Property(property="temp_token", type="string", example="temp_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid OTP")
     *         )
     *     )
     * )
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
            'otp' => 'required|string|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($this->otpService->verifyOtp($request->phone, $request->otp)) {
            $user = User::where('phone', $request->phone)->first();
            $user->phone_verified_at = now();
            $user->save();

            $tempToken = $user->createToken('temp_token')->plainTextToken;

            if ($user->password) {
                return response()->json([
                    'status' => 'login',
                    'message' => 'Phone verified, please login.'
                ]);
            } else {
                return response()->json([
                    'status' => 'set_password',
                    'message' => 'OTP verified, set your password.',
                    'temp_token' => $tempToken
                ]);
            }
        }

        return response()->json(['error' => 'Invalid OTP'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/set-user-password",
     *     tags={"Auth"},
     *     summary="Set user password",
     *     description="Set the password for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string", example="new_password"),
     *             @OA\Property(property="password_confirmation", type="string", example="new_password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Password set successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password set successfully."),
     *             @OA\Property(property="token", type="string", example="token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function setPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Password set successfully.',
            'token' => $token
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login",
     *     description="Login with phone and password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="0991111111"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful."),
     *             @OA\Property(property="token", type="string", example="token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout",
     *     description="Logout the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     tags={"Auth"},
     *     summary="Get current user",
     *     description="Get the authenticated user's details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="phone", type="string", example="0991111111"),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", example="2022-01-01T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2022-01-01T00:00:00.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }
}
