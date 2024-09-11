<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductFilterRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Services\Bonus\BonusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiProductController extends Controller
{
    protected $bonusService;

    public function __construct(BonusService $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    public function viewProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw ValidationException::withMessages(['user' => 'Unauthorized']);
        }

        $this->bonusService->handleProductView($user, $request->product_id);

        return response()->json(['message' => 'Product view recorded.']);
    }

    public function getProductViewBonusProgress(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $progress = $this->bonusService->getProductViewBonusProgress($user);

        return response()->json($progress);
    }

    public function show($id)
    {

        $product = $this->getProductWithRelations( $id );

        if (!$product) {
            return $this->productNotFoundResponse();
        }

        $productDetails = $this->prepareProductDetails($product);

        $user = Auth::guard('api')->user();

        $this->bonusService->handleProductView($user, $id);

        // Return the product details in JSON format
        return response()->json([
            'success' => true,
            'data' => $productDetails
        ], 200);

    }

    private function getProductWithRelations($id)
    {
        return Product::with([
            'images',
            'products',
            'attributeValues.attribute',
            'colorVariations.images',
            'topLists',
            'colorVariations.attributeValues.attribute',
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
        return [
            'product' => $product->only([
                'id', 'name', 'sku', 'description', 'price', 'final_price', 'stock', 'discount',
                'discount_ends_at', 'is_set', 'is_in_basket', 'is_favorite', 'remaining_discount_seconds',
                'has_unlimited_discount', 'has_limited_discount', 'average_rating'
            ]),
            'set_modules' => $this->getSetModules($product),
            'images' => $this->getImages($product) ,
            'color_variations' => $product->parent_id === NULL ? $this->getColorVariations($product) : $this->getSiblingColorVariations($product),
            'reviews' => $this->getAcceptedReviews($product),
            'similar_products' => $this->getSimilarProducts($product),
            'purchased_together_products' => $this->getPurchasedTogetherProducts($product),
            'top_list' => $this->getTopList($product),
        ];
    }
    private function getImages( $product )
    {
        return $product->images->map(function ($image) {
            return [
                'id' => $image->id,
                'product_id' => $image->product_id,
                'image_path' => url('storage/images/products/' . $image->image_path),
                'is_main' => $image->is_main,
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
                'has_limited_discount' => $similiarProduct->has_limited_discount
            ];
        });
    }

    private function getPurchasedTogetherProducts($product)
    {
        return $product->purchasedTogetherProducts->map(function ($purchasedTogetherProduct) {
            return [
                'id' => $purchasedTogetherProduct->id,
                'name' => $purchasedTogetherProduct->name,
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
                'has_limited_discount' => $purchasedTogetherProduct->has_limited_discount
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
                    'has_limited_discount' => $topListProduct->product->has_limited_discount
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


    public function filter(ProductFilterRequest $request)
    {
        $query = Product::main();

        foreach ($request->input('attributes') as $attribute) {
            $query->whereHas('attributeValues', function ($q) use ($attribute) {
                $q->where('attribute_id', $attribute['id'])
                    ->where('value', $attribute['value']);
            });
        }

        $products = $query->with(['images', 'attributeValues.attribute', 'variations.images', 'variations.attributeValues.attribute'])->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());

        if ($request->has('images')) {
            foreach ($request->images as $image) {
                ProductImage::create(['product_id' => $product->id, 'image_path' => $image]);
            }
        }

        if ($request->is_set && $request->has('products')) {
            foreach ($request->products as $productData) {
                $product->products()->attach($productData['product_id'], ['quantity' => $productData['quantity']]);
            }
        }

        return response()->json($product, 201);
    }


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
        });

        // Return the products as a JSON response
        return response()->json($transformedProducts);
    }
}
