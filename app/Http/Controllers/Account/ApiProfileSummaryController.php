<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiProfileSummaryController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->user = Auth::guard('sanctum')->user();
    }

    /**
     * @OA\Get(
     *     path="/api/get-profile-summary",
     *     operationId="getUserProfileSummary",
     *     tags={"Profile"},
     *     summary="Retrieve the profile summary for the authenticated user",
     *     description="Returns a summary of the authenticated user's profile, including full name, bonus amount, number of favorites, and order counts by status.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Profile summary retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=true),
     *             @OA\Property(property="data", type="object", description="User profile data",
     *                 @OA\Property(property="fullname", type="string", description="User's full name", example="John Doe"),
     *                 @OA\Property(property="bonus_amount", type="integer", description="Remaining bonus amount", example=150),
     *                 @OA\Property(property="favorites_count", type="integer", description="Number of favorite products", example=5),
     *                 @OA\Property(
     *                     property="order_counts",
     *                     type="object",
     *                     description="Counts of orders by status",
     *                     @OA\Property(property="pending", type="integer", description="Count of pending orders", example=2),
     *                     @OA\Property(property="preparing", type="integer", description="Count of preparing orders", example=1),
     *                     @OA\Property(property="shipping", type="integer", description="Count of shipping orders", example=0),
     *                     @OA\Property(property="delivered", type="integer", description="Count of delivered orders", example=7)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", description="Response message", example="User details retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="User not authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving user details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="An error occurred while retrieving user details"),
     *             @OA\Property(property="error", type="string", description="Detailed error message", example="SQLSTATE[42S22]: Column not found: 1054 Unknown column...")
     *         )
     *     )
     * )
     */
    public function getDetails()
    {
        try {
            // Ensure the user is authenticated and available
            if (!$this->user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Gather details
            $userFullName = $this->getUserFullName();
            $bonusAmount = (int)$this->user->remaining_bonus_amount ?? 0;
            $favoritesCount = $this->user->favorites->count() ?? 0;
            $orderCounts = $this->getOrderCounts();

            // Respond with success and user details
            return response()->json([
                'success' => true,
                'data' => [
                    'fullname' => $userFullName,
                    'bonus_amount' => $bonusAmount,
                    'favorites_count' => $favoritesCount,
                    'order_counts' => $orderCounts,
                ],
                'message' => 'User details retrieved successfully',
            ], 200);

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving user details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function getUserFullName(): string
    {
        // Safely return user's full name
        return trim(($this->user->name ?? '') . ' ' . ($this->user->surname ?? ''));
    }

    private function getOrderCounts(): array
    {
        try {
            // List of valid statuses
            $statuses = ['pending', 'preparing', 'shipping', 'delivered'];
            // Query order counts by status
            $orderCounts = $this->user->orders()
                ->select('status', DB::raw('count(*) as count'))
                ->whereIn('status', $statuses)
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            // Map to ensure all statuses are represented
            return collect($statuses)->mapWithKeys(function ( $status ) use ( $orderCounts ) {
                return [$status => $orderCounts->get($status)->count ?? 0];
            })->toArray();

        } catch (\Exception $e) {
            // Handle errors in counting orders
            return [
                'error' => 'Could not retrieve order counts',
                'message' => $e->getMessage(),
            ];
        }
    }

}
