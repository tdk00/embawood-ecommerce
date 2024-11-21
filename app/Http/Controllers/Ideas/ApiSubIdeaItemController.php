<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\SubIdeaItem;
use Illuminate\Http\Request;

class ApiSubIdeaItemController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/sub-idea-items",
     *     operationId="getAllSubIdeaItems",
     *     tags={"SubIdeaItems"},
     *     summary="Retrieve all sub-idea items",
     *     description="Fetches a list of all sub-idea items along with their associated products and images.",
     *     @OA\Response(
     *         response=200,
     *         description="List of sub-idea items retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Sub-idea item ID", example=1),
     *                 @OA\Property(property="title", type="string", description="Sub-idea item title", example="Modern Living Room Ideas"),
     *                 @OA\Property(property="description", type="string", description="Sub-idea item description", example="Inspiration for modern living room layouts."),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     description="List of associated images",
     *                     @OA\Items(
     *                         @OA\Property(property="image_url", type="string", description="URL of the image", example="http://example.com/storage/images/ideas/image1.jpg")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="List of associated products",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", description="Product ID", example=1001),
     *                         @OA\Property(property="name", type="string", description="Product name", example="Modern Sofa"),
     *                         @OA\Property(property="price", type="number", format="float", description="Product price", example=999.99),
     *                         @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=899.99),
     *                         @OA\Property(property="discount", type="number", format="float", description="Discount percentage", example=10),
     *                         @OA\Property(property="main_image", type="string", description="URL of the product's main image", example="http://example.com/storage/images/products/sofa.jpg"),
     *                         @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                         @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is marked as a favorite", example=true)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $subIdeaItems = SubIdeaItem::with(['products', 'images'])->get();
        return response()->json($subIdeaItems);
    }

    /**
     * @OA\Get(
     *     path="/api/sub-idea-items/{subIdeaItem}",
     *     operationId="getSubIdeaItemDetails",
     *     tags={"SubIdeaItems"},
     *     summary="Retrieve details of a specific sub-idea item",
     *     description="Fetches the details of a specific sub-idea item, including its associated products and images.",
     *     @OA\Parameter(
     *         name="subIdeaItem",
     *         in="path",
     *         required=true,
     *         description="ID of the sub-idea item",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sub-idea item details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Sub-idea item ID", example=1),
     *             @OA\Property(property="name", type="string", description="Sub-idea item title", example="Modern Living Room Ideas"),
     *             @OA\Property(property="description", type="string", description="Sub-idea item description", example="Inspiration for modern living room layouts."),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 description="List of associated images",
     *                 @OA\Items(
     *                     @OA\Property(property="image_url", type="string", description="URL of the image", example="http://example.com/storage/images/ideas/image1.jpg")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of associated products",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Product ID", example=1001),
     *                     @OA\Property(property="name", type="string", description="Product name", example="Modern Sofa"),
     *                     @OA\Property(property="price", type="number", format="float", description="Product price", example=999.99),
     *                     @OA\Property(property="final_price", type="number", format="float", description="Final price after discount", example=899.99),
     *                     @OA\Property(property="discount", type="number", format="float", description="Discount percentage", example=10),
     *                     @OA\Property(property="main_image", type="string", description="URL of the product's main image", example="http://example.com/storage/images/products/sofa.jpg"),
     *                     @OA\Property(property="is_in_basket", type="boolean", description="Indicates if the product is in the user's basket", example=false),
     *                     @OA\Property(property="is_favorite", type="boolean", description="Indicates if the product is marked as a favorite", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sub-idea item not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Sub-idea item not found")
     *         )
     *     )
     * )
     */
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
                    'image_url' => url('storage/images/ideas/' . $image->image_url),
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
                    'has_limited_discount' => $product->has_limited_discount,
                    'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
                    'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
                ];
            })
        ];
        return response()->json($data);

    }
}
