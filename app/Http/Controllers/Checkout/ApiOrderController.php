<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Checkout\Order;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     operationId="getUserOrders",
     *     tags={"Orders"},
     *     summary="Retrieve all orders for the authenticated user",
     *     description="Returns a list of orders belonging to the authenticated user with basic information.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of user orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Order ID", example=1),
     *                 @OA\Property(property="code", type="string", description="Order code", example="NYC1C"),
     *                 @OA\Property(property="date", type="string", format="date", description="Order creation date", example="15.11.2024"),
     *                 @OA\Property(property="status", type="string", description="Order status", example="pending")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)->get();

        $transformedOrders = $orders->map(function ($order) {
        return [
            'id' => $order->id,
            'code' => 'NYC'.$order->id.'C',
            'date' => $order->created_at->format('d.m.Y'),
            'status' => $order->status,
        ];
    });

        return response()->json($transformedOrders);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/status/{status}",
     *     operationId="getUserOrdersByStatus",
     *     tags={"Orders"},
     *     summary="Retrieve orders by status",
     *     description="Returns a list of orders filtered by their status for the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         description="Order status to filter by",
     *         @OA\Schema(type="string", example="pending")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of orders filtered by status",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Order ID", example=1),
     *                 @OA\Property(property="status", type="string", description="Order status", example="pending"),
     *                 @OA\Property(property="date", type="string", format="date", description="Order creation date", example="15.11.2024"),
     *                 @OA\Property(property="code", type="string", description="Order code", example="NYC1C")
     *             )
     *         )
     *     )
     * )
     */
    public function getByStatus($status)
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)
            ->where('status', $status)
            ->get();

        return response()->json($orders);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/date-range",
     *     operationId="getUserOrdersByDateRange",
     *     tags={"Orders"},
     *     summary="Retrieve orders within a specific date range",
     *     description="Returns a list of orders for the authenticated user that fall within a specified date range.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=true,
     *         description="Start date of the range in YYYY-MM-DD format",
     *         @OA\Schema(type="string", format="date", example="2024-11-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=true,
     *         description="End date of the range in YYYY-MM-DD format",
     *         @OA\Schema(type="string", format="date", example="2024-11-15")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of orders within the date range",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Order ID", example=1),
     *                 @OA\Property(property="date", type="string", format="date", description="Order creation date", example="2024-11-10"),
     *                 @OA\Property(property="status", type="string", description="Order status", example="pending"),
     *                 @OA\Property(property="code", type="string", description="Order code", example="NYC1C")
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     operationId="getOrderDetails",
     *     tags={"Orders"},
     *     summary="Retrieve order details",
     *     description="Returns detailed information about a specific order for the authenticated user, including items and discounts.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(
     *             @OA\Property(property="order_total", type="number", format="float", description="Order total before discounts", example=200.5),
     *             @OA\Property(property="final_total", type="number", format="float", description="Order total after all discounts", example=180.0),
     *             @OA\Property(property="item_discounts_total", type="number", format="float", description="Total item discounts applied", example=15.0),
     *             @OA\Property(property="coupon_discount", type="number", format="float", description="Total coupon discount applied", example=5.5),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="List of items in the order",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Item ID", example=1),
     *                     @OA\Property(property="product_id", type="integer", description="Product ID", example=101),
     *                     @OA\Property(property="product_name", type="string", description="Name of the product", example="Wooden Chair"),
     *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
     *                     @OA\Property(property="price", type="number", format="float", description="Price of the product", example=100.0),
     *                     @OA\Property(property="discount_amount", type="number", format="float", description="Discount amount applied to the item", example=10.0),
     *                     @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=90.0),
     *                     @OA\Property(property="main_image", type="string", description="URL of the product's main image", example="https://example.com/image.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Order not found")
     *         )
     *     )
     * )
     */
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
            $productWithoutScope = Product::withoutGlobalScope('active')->find($item->product_id);

            // Set the image for the item
            $item->image = url('storage/images/ideas/' . $productWithoutScope->main_image);
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $productWithoutScope->name,
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

    /**
     * @OA\Get(
     *     path="/api/orders/{id}/all-statuses",
     *     operationId="getOrderDeliveryStatus",
     *     tags={"Orders"},
     *     summary="Retrieve delivery statuses for an order",
     *     description="Returns the delivery statuses of a specific order, including the current status and the date each status was reached.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order delivery statuses",
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="integer", description="Order ID", example=1),
     *             @OA\Property(
     *                 property="statuses",
     *                 type="array",
     *                 description="List of delivery statuses with dates",
     *                 @OA\Items(
     *                     @OA\Property(property="status", type="string", description="Status name", example="shipping"),
     *                     @OA\Property(property="changed_at", type="string", format="datetime", nullable=true, description="Date when the status was reached", example="2024-11-15 10:30:00"),
     *                     @OA\Property(property="is_current", type="boolean", description="Indicates if this is the current status", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Order not found")
     *         )
     *     )
     * )
     */
    public function getDeliveryStatus( $id ){
        $statuses = ['pending', 'preparing', 'shipping', 'delivered'];

        $userId = Auth::id();
        $order = Order::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Retrieve all status histories for the order, ordered by date
        $statusHistories = $order->statusHistories()->orderBy('changed_at', 'asc')->get();

        // Create an array to hold statuses and dates along with the is_current flag
        $statusesWithDates = [];

        // Initialize the structure with empty dates and false is_current
        foreach ($statuses as $status) {
            $statusesWithDates[] = [
                'status' => $status,
                'changed_at' => null,
                'is_current' => false
            ];
        }

        // Fill in the dates from the status history
        foreach ($statusesWithDates as &$statusData) {
            foreach ($statusHistories as $history) {
                if ($statusData['status'] === $history->status) {
                    $statusData['changed_at'] = $history->changed_at->format('Y-m-d H:i:s');
                }
            }

            // Set the is_current flag for the current status
            if ($statusData['status'] === $order->status) {
                $statusData['is_current'] = true;
            }
        }



        return response()->json([
            'order_id' => $order->id,
            'statuses' => $statusesWithDates
        ]);

    }
}
