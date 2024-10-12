<?php
namespace App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use App\Models\Category\Category;
use App\Models\Category\Subcategory;
use App\Models\Checkout\Order;
use App\Models\Product\Product;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Məhsullar (Products)
        $availableProducts = Product::count();
        $categoryProductCounts = Subcategory::withCount('products')->get(); // Assuming Category has products relationship
        $availableSets = Product::where('is_set', true)->count();

        // Kateqoriyalar (Categories)
        $totalCategories = Category::count();
        $totalSubCategories = Subcategory::whereNotNull('category_id')->count(); // Assuming subcategories have parent_id

        // Sifarişlər (Orders)
        $totalOrders = Order::count();
        $dailyOrders = Order::selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        $dailyOrdersFormatted = array_fill(0, 24, 0);

        foreach ($dailyOrders as $hour => $total) {
            $dailyOrdersFormatted[$hour] = $total;
        }

        $weeklyOrders = Order::selectRaw('DAYOFWEEK(created_at) as day, COUNT(*) as total')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $weeklyOrdersFormatted = array_fill(1, 7, 0);

        foreach ($weeklyOrders as $day => $total) {
            $weeklyOrdersFormatted[$day] = $total;
        }

        $monthlyOrders = Order::selectRaw('DAYOFMONTH(created_at) as day, COUNT(*) as total')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $monthlyOrdersFormatted = array_fill(1, Carbon::now()->daysInMonth, 0);

        foreach ($monthlyOrders as $day => $total) {
            $monthlyOrdersFormatted[$day] = $total;
        }


        // İstifadəçilər (Users)
        $verifiedUsers = User::whereNotNull('phone_verified_at')->count();
        $dailyVerifiedUsers = User::whereNotNull('phone_verified_at')
            ->whereDate('created_at', Carbon::today())->count();
        $usersWithOrders = User::has('orders')->count(); // Assuming User has orders relationship

        // Return the data to the view
        return view('admin.pages.dashboard.dashboard', compact(
            'dailyOrdersFormatted',
            'weeklyOrdersFormatted',
            'monthlyOrdersFormatted',
            'availableProducts',
            'categoryProductCounts',
            'availableSets',
            'totalCategories',
            'totalSubCategories',
            'totalOrders',
            'verifiedUsers',
            'dailyVerifiedUsers',
            'usersWithOrders'
        ));
    }
}
