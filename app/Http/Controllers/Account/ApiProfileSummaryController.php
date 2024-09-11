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
