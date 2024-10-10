<?php
namespace App\Http\Controllers\Admin\Order;
use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use App\Models\Basket\BasketItem;
use App\Models\Category\Subcategory;
use App\Models\Checkout\Order;
use App\Models\Checkout\OrderStatusHistory;
use App\Models\Product\Favorite;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the products.
     */
    protected $statusOrder = [
        'pending',
        'preparing',
        'shipping',
        'delivered'
    ];
    public function index()
    {
        // You can add filtering and pagination here
        $orders = Order::orderBy('created_at', 'desc')->get();
        $statusMapping = [
            'pending' => 'Pending',
            'preparing' => 'Preparing',
            'shipping' => 'Shipping',
            'delivered' => 'Delivered',
        ];

        $badgeClassMapping = [
            'pending' => 'badge-light-warning',
            'preparing' => 'badge-light-info',
            'shipping' => 'badge-light-primary',
            'delivered' => 'badge-light-success',
        ];

        return view('admin.pages.orders.index', compact('orders', 'statusMapping', 'badgeClassMapping'));
    }

    public function edit( $id ){
        $order = Order::find($id);

        // Retrieve only the products (excluding sets but including set's products)
        $setIds = $order->items()
            ->whereNotNull('set_id')
            ->pluck('set_id'); // Extract all set_id values

        // Retrieve only the products (excluding sets but including set's products)
        $orderItems = $order->items()
//            ->whereNull('set_id') // Individual products not part of a set
//            ->whereNotIn('product_id', $setIds) // Exclude products that are sets
//            ->orWhereIn('set_id', $setIds) // Include products that belong to a set
            ->get();

        $orderItems = $orderItems->map(function ($item) {
            $item->product->image = url('storage/images/products/' . $item->product->main_image);
            return $item;
        });

        // Pass both the order and filtered orderItems to the view
        return view('admin.pages.orders.edit', compact('order', 'orderItems'));
    }

    public function changeStatus(Request $request, $id)
    {
        // Validate the request to ensure a valid status is provided
        $validated = $request->validate([
            'status' => 'required|string|in:pending,preparing,shipping,delivered',
        ]);

        // Find the order
        $order = Order::findOrFail($id);

        // Update the status of the order
        $order->update(['status' => $validated['status']]);

        // Get the index of the selected status in the fixed status order array
        $selectedStatusIndex = array_search($validated['status'], $this->statusOrder);

        // Fetch all status histories for the order, ordered by 'changed_at'
        $statusHistories = $order->statusHistories()->orderBy('changed_at', 'asc')->get();

        // Iterate through the status histories and delete the same status and the ones after it
        foreach ($statusHistories as $history) {
            // Get the index of the current status in the loop
            $currentStatusIndex = array_search($history->status, $this->statusOrder);

            // If the current status is the same or comes after the selected status, delete it
            if ($currentStatusIndex >= $selectedStatusIndex) {
                $history->delete();
            }
        }

        // Log the status change in the history table
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $validated['status'],
            'changed_at' => now(),
        ]);

        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Order status updated successfully!']);
    }
}
