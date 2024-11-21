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
     *     path="/api/auth/authenticate",
     *     operationId="authenticateUser",
     *     tags={"Authentication"},
     *     summary="Authenticate user via phone number",
     *     description="Handles user authentication by verifying the phone number. The phone number should follow the format (XX) XXX-XX-XX.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 description="User's phone number in the format (XX) XXX-XX-XX",
     *                 example="(33) 445-55-55",
     *                 pattern="\(\d{2}\) \d{3}-\d{2}-\d{2}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication or OTP required",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Authentication status", example="otp_required"),
     *             @OA\Property(property="message", type="string", description="Response message", example="OTP sent to your phone number"),
     *             @OA\Property(property="otp", type="string", nullable=true, description="Generated OTP if applicable", example="1234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", description="Validation errors", example={"phone": {"The phone field is required."}})
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
     *     path="/api/auth/verify-otp",
     *     operationId="verifyOtp",
     *     tags={"Authentication"},
     *     summary="Verify the OTP for phone number authentication",
     *     description="Verifies the OTP sent to the user's phone. If successful, marks the phone as verified and either prompts the user to log in or set a password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 maxLength=15,
     *                 description="User's phone number in the format (XX) XXX-XX-XX",
     *                 example="(33) 445-55-55",
     *                 pattern="\(\d{2}\) \d{3}-\d{2}-\d{2}"
     *             ),
     *             @OA\Property(
     *                 property="otp",
     *                 type="string",
     *                 maxLength=4,
     *                 description="One-time password sent to the user's phone",
     *                 example="1234"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP successfully verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Authentication status", example="set_password"),
     *             @OA\Property(property="message", type="string", description="Response message", example="OTP verified, set your password."),
     *             @OA\Property(property="temp_token", type="string", nullable=true, description="Temporary token for password setup", example="abcd1234efgh5678ijkl91011mnop12")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message", example="Invalid OTP")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", description="Validation errors", example={"phone": {"The phone field is required."}, "otp": {"The otp field is required."}})
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
     *     path="/api/auth/set-password",
     *     operationId="setPassword",
     *     tags={"Authentication"},
     *     summary="Set a new password for the authenticated user",
     *     description="Allows the user to set a new password. Requires the password confirmation and ensures the password meets the minimum length requirement.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="New password (must be confirmed)",
     *                 example="newpassword123"
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 description="Password confirmation",
     *                 example="newpassword123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Password set successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Response status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Password set successfully."),
     *             @OA\Property(property="token", type="string", description="Bearer token for authenticated access", example="abcd1234efgh5678ijkl91011mnop12")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", description="Validation errors", example={"password": {"The password field is required.", "The password must be at least 6 characters."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message", example="User not found")
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

        $user->tokens()->delete();

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
     *     path="/api/auth/login",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     summary="Log in a user",
     *     description="Authenticates the user using their phone number and password. Returns an authentication token upon successful login.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 maxLength=15,
     *                 description="User's phone number",
     *                 example="(33) 445-55-55",
     *                 pattern="\(\d{2}\) \d{3}-\d{2}-\d{2}"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="User's password",
     *                 example="userpassword"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Response status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Login successful."),
     *             @OA\Property(property="token", type="string", description="Bearer token for authenticated access", example="abcd1234efgh5678ijkl91011mnop12")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", description="Validation errors", example={"phone": {"The phone field is required."}, "password": {"The password field is required."}})
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
     *     path="/api/auth/logout",
     *     operationId="logoutUser",
     *     tags={"Authentication"},
     *     summary="Log out the authenticated user",
     *     description="Revokes the current access token for the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Response status", example="success"),
     *             @OA\Property(property="message", type="string", description="Response message", example="Successfully logged out")
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
     *     path="/api/auth/me",
     *     operationId="getAuthenticatedUser",
     *     tags={"Authentication"},
     *     summary="Retrieve authenticated user details",
     *     description="Returns basic information about the currently authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="Response status", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="User data",
     *                 @OA\Property(property="id", type="integer", description="User ID", example=1),
     *                 @OA\Property(property="name", type="string", description="User's first name", example="John"),
     *                 @OA\Property(property="surname", type="string", description="User's last name", example="Doe"),
     *                 @OA\Property(property="phone", type="string", description="User's phone number", example="(33) 445-55-55"),
     *                 @OA\Property(property="remaining_bonus_amount", type="number", format="float", description="User's remaining bonus amount", example=150.5)
     *             )
     *         )
     *     )
     * )
     */
    public function me(Request $request)
    {
        $user = $request->user()->only([
            'id',
            'name',
            'surname',
            'phone',
            'remaining_bonus_amount'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
