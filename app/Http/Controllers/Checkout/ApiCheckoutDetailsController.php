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
