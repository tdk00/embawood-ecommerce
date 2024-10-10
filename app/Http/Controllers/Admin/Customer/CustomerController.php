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
        // You can add filtering and pagination here
        $customers = User::orderBy('id', 'desc')->get();
//
//        $transformedNewProducts = $products->map(function ($product) {
//
//            $product->image = url('storage/images/products/' . $product->main_image);
//            $product->image_hover = url('storage/images/products/' . $product->hover_image);
//            $productData = [
//                'id' => $product->id,
//                'name' => $product->name,
//                'image' => $product->image,
//                'category_name' => $product->subcategories()?->first()?->name ?? "",
//                'discount' => $product->discount,
//                'discount_ends_at' => $product->discount_ends_at,
//                'price' => $product->price,
//                'final_price' => $product->final_price,
//                'average_rating' => $product->average_rating,
//                'image_hover' => $product->image_hover,
//                'is_in_basket' => $product->is_in_basket,
//                'is_favorite' => $product->is_favorite,
//                'slug' => $product->slug,
//            ];
//            return $productData;
//        });

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
