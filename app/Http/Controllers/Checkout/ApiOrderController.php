<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Checkout\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{
    // Get all orders for the authenticated user
    public function index()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)->get();

        return response()->json($orders);
    }

    // Get orders by status
    public function getByStatus($status)
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)
            ->where('status', $status)
            ->get();

        return response()->json($orders);
    }

    // Get orders by date range
    public function getByDateRange(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = Order::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return response()->json($orders);
    }

    // Get order details by order ID
    public function show($id)
    {
        $userId = Auth::id();
        $order = Order::where('user_id', $userId)
            ->where('id', $id)
            ->with('items.product')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }
}
