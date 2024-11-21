<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Checkout\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCheckoutDetailsController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->user = Auth::guard('sanctum')->user();
    }


    /**
     * @OA\Post(
     *     path="/api/checkout/details",
     *     operationId="getCheckoutDetails",
     *     tags={"Checkout"},
     *     summary="Retrieve checkout details including applicable bonus usage",
     *     description="Calculates the maximum bonus amount that can be applied to a checkout based on the final total and the user's remaining bonus amount.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="final_total", type="number", format="float", description="Final total amount for the checkout", example=150.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Checkout details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="bonus_can_be_used", type="number", format="float", description="Maximum bonus amount that can be applied to this checkout", example=100.0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="User not authenticated.")
     *         )
     *     )
     * )
     */
    public function getDetails(Request $request)
    {
        // Accept final_amount from request and cast it to float
        $final_total = (float) $request->input('final_total');

        // Cast remaining_bonus_amount to float as it may come as a string from the DB
        $remaining_bonus_amount = (float) $this->user->remaining_bonus_amount;

        // Calculate bonus_can_be_used based on the condition
        if ($remaining_bonus_amount / 100 > $final_total) {
            $bonus_can_be_used = $final_total * 100;
        } else {
            $bonus_can_be_used = $remaining_bonus_amount;
        }

        // Return a response with bonus_can_be_used
        return response()->json([
            'bonus_can_be_used' => $bonus_can_be_used,
        ]);
    }
}
