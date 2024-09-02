<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\SubIdeaItem;
use Illuminate\Http\Request;

class ApiSubIdeaItemController extends Controller
{

    public function index()
    {
        $subIdeaItems = SubIdeaItem::with(['products', 'images'])->get();
        return response()->json($subIdeaItems);
    }

    public function show(SubIdeaItem $subIdeaItem)
    {
        $subIdeaItem->load(['products', 'images']);


        $products = $subIdeaItem->products;
        $data =  [
            'id' => $subIdeaItem->id,
            'name' => $subIdeaItem->title,
            'description' => $subIdeaItem->description,
            'images' => $subIdeaItem->images->map(function ($image) {
                return [
                    'image_url' => $image->image_url
                ];
            }),
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'is_set' => $product->is_set,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'discount_ends_at' => $product->discount_ends_at,
                    'final_price' => $product->final_price,
                    'main_image' => url('storage/images/products/' . $product->main_image),
                    'average_rating' => $product->average_rating,
                    'is_in_basket' => $product->is_in_basket,
                    'is_favorite' => $product->is_favorite,
                    'remaining_discount_seconds' => $product->remaining_discount_seconds,
                    'has_unlimited_discount' => $product->has_unlimited_discount,
                    'has_limited_discount' => $product->has_limited_discount
                ];
            })
        ];
        return response()->json($data);

    }
}
