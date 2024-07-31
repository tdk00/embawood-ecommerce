<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Favorite;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiFavoriteController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $favorites = Favorite::where('user_id', $userId)->with('product')->get();

        return response()->json($favorites);
    }

    public function toggle(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        $product = Product::findOrFail($productId);

        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Product removed from favorites']);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json(['message' => 'Product added to favorites']);
        }
    }
}
