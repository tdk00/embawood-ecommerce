<?php
namespace App\Http\Controllers\Admin\Customer;
use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use App\Models\Basket\BasketItem;
use App\Models\Category\Subcategory;
use App\Models\Product\Favorite;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $customers = User::withCount('orders')
            ->withSum('orders', 'total')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.pages.customers.index', compact('customers'));
    }

    public function edit( $id ){
        $customer = User::find( $id );
        $basketItems = BasketItem::where('identifier', $id)->whereNull('set_id')->get();
        $favorites = Favorite::where('user_id', $id)->get(); // todo: bug


        $basketItems = $basketItems->map(function ($item) {
            $item->product->image = url('storage/images/products/' . $item->product->main_image);
            return $item;
        });

        $favorites = $favorites->map(function ($item) {
            $item->product->image = url('storage/images/products/' . $item->product->main_image);
            return $item;
        });

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

        return view('admin.pages.customers.edit', compact('customer', 'basketItems', 'favorites', 'statusMapping', 'badgeClassMapping'));
    }
}
