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
        $product = Product::with([
            'images',
            'attributeValues.attribute',
            'colorVariations.images',
            'topLists.subcategory',
            'colorVariations.attributeValues.attribute',
        ])->find($id);

        return response()->json($product);
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
}
