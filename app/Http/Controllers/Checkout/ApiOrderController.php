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

        $transformedOrders = $orders->map(function ($order) {
        return [
            'id' => $order->id,
            'code' => 'NYC'.$order->id.'C',
            'date' => $order->created_at->format('d.m.Y'),
            'status' => $order->status == "pending" ? "Gözləmədə" : "Ləğv edilib",
        ];
    });

        return response()->json($transformedOrders);
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
            ->with('items.product.images')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $items = $order->items->map(function ($item) {
            $item->image = url('storage/images/ideas/' . $item->product->main_image);
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'discount_amount' => $item->discount_amount,
                'final_price' => ($item->price - $item->discount_amount),
                'main_image' => url('storage/images/products/' . $item->product->main_image),
            ];
        });

        return response()->json([
            'order_total' => $order->total,
            'final_total' => ($order->total - $order->item_discounts_total - $order->coupon_discount),
            'item_discounts_total' => $order->item_discounts_total,
            'coupon_discount' => $order->coupon_discount,
            'items' => $items
        ]);
    }
}
