<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductFilterRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Services\Bonus\BonusService;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiProductController extends Controller
{
    protected $bonusService;
    protected $creditService;

    public function __construct(BonusService $bonusService, CreditService $creditService)
    {
        $this->bonusService = $bonusService;
        $this->creditService = $creditService;
    }


    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     operationId="getProductDetails",
     *     tags={"Products"},
     *     summary="Retrieve product details",
     *     description="Returns detailed information about a specific product, including images, reviews, credit options, and similar products.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID or slug",
     *         @OA\Schema(
     *             type="string",
     *             example="123"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", description="Product details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show($identifier)
    {
        // Determine if the identifier is a numeric ID or a slug
        $product = is_numeric($identifier)
            ? $this->getProductWithRelations($identifier)
            : $this->getProductWithRelationsBySlug($identifier);

        if (!$product) {
            return $this->productNotFoundResponse();
        }

        $productDetails = $this->prepareProductDetails($product);

        $user = Auth::guard('api')->user();

        $this->bonusService->handleProductView($user, $product->id);

        // Return the product details in JSON format
        return response()->json([
            'success' => true,
            'data' => $productDetails
        ], 200);
    }

    protected function getProductWithRelationsBySlug($slug)
    {
        return Product::where('slug', $slug)
            ->with([
                'images',
                'products',
                'colorVariations.images',
                'topLists',
            ])
            ->first();
    }

    private function getProductWithRelations($id)
    {
        return Product::with([
            'images',
            'products',
            'colorVariations.images',
            'topLists',
        ])->find($id);
    }

    private function productNotFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Product not found'
        ], 404);
    }

    private function prepareProductDetails($product)
    {
        $creditOptions = $this->creditService->getOptions($product->final_price);
        return [
            'product' => $product->only([
                'id', 'name', 'sku', 'slug', 'description', 'price', 'final_price', 'stock', 'discount',
                'discount_ends_at', 'is_set', 'is_in_basket', 'is_favorite', 'remaining_discount_seconds',
                'has_unlimited_discount', 'has_limited_discount', 'average_rating', 'badge_1', 'badge_2', 'ar_model_url'
            ]),
            'set_modules' => $this->getSetModules($product),
            'images' => $this->getImages($product) ,
            'color_variations' => $product->parent_id === NULL ? $this->getColorVariations($product) : $this->getSiblingColorVariations($product),
            'reviews' => $this->getAcceptedReviews($product),
            'similar_products' => $this->getSimilarProducts($product),
            'purchased_together_products' => $this->getPurchasedTogetherProducts($product),
            'top_list' => $this->getTopList($product),
            'credit_options' => $creditOptions,
            'badge' => $product->badge_1 ? url('storage/images/badge/' . $product->badge_1) : null,
            'badge2' => $product->badge_2 ? url('storage/images/badge/' . $product->badge_2) : null,
        ];
    }
    private function getImages( $product )
    {
        return $product->images()->orderBy('order')->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'product_id' => $image->product_id,
                'image_path' => url('storage/images/products/' . $image->image_path),
                'is_main' => $image->is_main,
                'alt_text' => $image->alt_text,
            ];
        });
    }

    private function getSetModules($product)
    {
        if( $product->products ) {
            return $product->products->map(function ($module) {
                return [
                    'id' => $module->id,
                    'name' => $module->name,
                    'slug' => $module->slug,
                    'subcategory_name' => $module->subcategories->first()?->name ?? "",
                    'image' => url('storage/images/products/' . $module->main_image),
                    'discount' => $module->discount,
                    'discount_ends_at' => $module->discount_ends_at,
                    'price' => $module->price,
                    'final_price' => $module->final_price,
                    'average_rating' => $module->average_rating,
                    'is_in_basket' => $module->is_in_basket,
                    'remaining_discount_seconds' => $module->remaining_discount_seconds,
                    'has_unlimited_discount' => $module->has_unlimited_discount,
                    'has_limited_discount' => $module->has_limited_discount,
                    'quantity' => $module?->pivot?->quantity ?? 0,
                    'badge' => $module->badge_1 ? url('storage/images/badge/' . $module->badge_1) : null,
                    'badge2' => $module->badge_2 ? url('storage/images/badge/' . $module->badge_2) : null,
                ];
            });
        }

        return null;

    }

    private function getColorVariations($product)
    {
        return $product->colorVariations->map(function ($variation) {
            return [
                'id' => $variation->id,
                'color' => $variation->color,
            ];
        });
    }

    private function getSiblingColorVariations($product)
    {
        return $product->siblingColorVariations()->map(function ($variation) {
            return [
                'id' => $variation->id,
                'color' => $variation->color,
            ];
        });
    }

    private function getAcceptedReviews($product)
    {
        return $product->reviews->where('status', 'accepted')->values();
    }

    private function getSimilarProducts($product)
    {
        return $product->similarProducts->map(function ($similiarProduct) {
            return [
                'id' => $similiarProduct->id,
                'name' => $similiarProduct->name,
                'slug' => $similiarProduct->slug,
                'subcategory_name' => $similiarProduct->subcategories->first()?->name ?? "",
                'main_image' => url('storage/images/products/' . $similiarProduct->main_image),
                'discount' => $similiarProduct->discount,
                'discount_ends_at' => $similiarProduct->discount_ends_at,
                'price' => $similiarProduct->price,
                'final_price' => $similiarProduct->final_price,
                'average_rating' => $similiarProduct->average_rating,
                'is_in_basket' => $similiarProduct->is_in_basket,
                'is_favorite' => $similiarProduct->is_favorite,
                'remaining_discount_seconds' => $similiarProduct->remaining_discount_seconds,
                'has_unlimited_discount' => $similiarProduct->has_unlimited_discount,
                'has_limited_discount' => $similiarProduct->has_limited_discount,
                'badge' => $similiarProduct->badge_1 ? url('storage/images/badge/' . $similiarProduct->badge_1) : null,
                'badge2' => $similiarProduct->badge_2 ? url('storage/images/badge/' . $similiarProduct->badge_2) : null,
            ];
        });
    }

    private function getPurchasedTogetherProducts($product)
    {
        return $product->purchasedTogetherProducts->map(function ($purchasedTogetherProduct) {
            return [
                'id' => $purchasedTogetherProduct->id,
                'name' => $purchasedTogetherProduct->name,
                'slug' => $purchasedTogetherProduct->slug,
                'subcategory_name' => $purchasedTogetherProduct->subcategories->first()?->name ?? "",
                'main_image' => url('storage/images/products/' . $purchasedTogetherProduct->main_image),
                'discount' => $purchasedTogetherProduct->discount,
                'discount_ends_at' => $purchasedTogetherProduct->discount_ends_at,
                'price' => $purchasedTogetherProduct->price,
                'final_price' => $purchasedTogetherProduct->final_price,
                'average_rating' => $purchasedTogetherProduct->average_rating,
                'is_in_basket' => $purchasedTogetherProduct->is_in_basket,
                'is_favorite' => $purchasedTogetherProduct->is_favorite,
                'remaining_discount_seconds' => $purchasedTogetherProduct->remaining_discount_seconds,
                'has_unlimited_discount' => $purchasedTogetherProduct->has_unlimited_discount,
                'has_limited_discount' => $purchasedTogetherProduct->has_limited_discount,
                'badge' => $purchasedTogetherProduct->badge_1 ? url('storage/images/badge/' . $purchasedTogetherProduct->badge_1) : null,
                'badge2' => $purchasedTogetherProduct->badge_2 ? url('storage/images/badge/' . $purchasedTogetherProduct->badge_2) : null,
            ];
        });
    }

    private function getTopList($product)
    {
        $topList = $product?->topLists?->first();

        if ($topList) {
            $products = $topList->category->topList->map(function ($topListProduct) {
                return [
                    'id' => $topListProduct->product->id,
                    'name' => $topListProduct->product->name,
                    'slug' => $topListProduct->product->slug,
                    'subcategory_name' => $topListProduct->product->subcategories->first()?->name ?? "",
                    'main_image' => url('storage/images/products/' . $topListProduct->product->main_image),
                    'discount' => $topListProduct->product->discount,
                    'discount_ends_at' => $topListProduct->product->discount_ends_at,
                    'price' => $topListProduct->product->price,
                    'final_price' => $topListProduct->product->final_price,
                    'average_rating' => $topListProduct->product->average_rating,
                    'is_in_basket' => $topListProduct->product->is_in_basket,
                    'is_favorite' => $topListProduct->product->is_favorite,
                    'remaining_discount_seconds' => $topListProduct->product->remaining_discount_seconds,
                    'has_unlimited_discount' => $topListProduct->product->has_unlimited_discount,
                    'has_limited_discount' => $topListProduct->product->has_limited_discount,
                    'badge' => $topListProduct->badge_1 ? url('storage/images/badge/' . $topListProduct->badge_1) : null,
                    'badge2' => $topListProduct->badge_2 ? url('storage/images/badge/' . $topListProduct->badge_2) : null,
                ];
            });

            return [
                'category_id' => $topList->category->id,
                'name' => $topList->category->name,
                'position' => $topList->position,
                'products' => $products
            ];
        }

        return null;
    }


    /**
     * @OA\Post(
     *     path="/api/products/filter",
     *     operationId="filterProducts",
     *     tags={"Products"},
     *     summary="Filter products",
     *     description="Returns a list of products based on specified filters.",
     *     @OA\RequestBody(
     *         required=false,
     *         description="Filters for products"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filtered products retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(type="object", description="Product details"))
     *     )
     * )
     */
    public function filter(ProductFilterRequest $request)
    {
        $query = Product::main();


        $products = $query->with(['images', 'variations.images'])->get();

        return response()->json($products);
    }


    /**
     * @OA\Get(
     *     path="/api/fetch-viewed-products-by-ids",
     *     operationId="fetchViewedProductsByIds",
     *     tags={"Products"},
     *     summary="Fetch products by viewed IDs",
     *     description="Fetches a list of products based on a comma-separated list of product IDs.",
     *     @OA\Parameter(
     *         name="ids",
     *         in="query",
     *         required=true,
     *         description="Comma-separated list of product IDs",
     *         @OA\Schema(type="string", example="1,2,3")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Viewed products retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object", description="Product details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="ids", type="array", @OA\Items(type="string", example="The ids field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function fetchViewedProductsByIds(Request $request)
    {
        // Validate the incoming request to ensure 'ids' is present
        $validatedData = $request->validate([
            'ids' => 'required|string',  // 'ids' should be a comma-separated string
        ]);

        $idsArray = explode(',', $validatedData['ids']);

        $products = Product::whereIn('id', $idsArray)->get();

        $transformedProducts = $products->map(function ($product) {
            $product->image = url('storage/images/products/' . $product->main_image);
            $product->image_hover = url('storage/images/products/' . $product->hover_image);
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
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
        });

        // Return the products as a JSON response
        return response()->json($transformedProducts);
    }
}
