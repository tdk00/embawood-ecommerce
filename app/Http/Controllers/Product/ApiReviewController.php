<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiReviewController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ];

        $user = Auth::guard('api')->user();

        if (!$user || ($user && (!$user->name || !$user->surname))) {
            $rules['name'] = 'required|string';
            $rules['surname'] = 'required|string';
        }

        if (!$user) {
            $rules['contact'] = 'required|string'; // For email or phone
        }

        $validated = $request->validate($rules);

        if ($user) {
            $review = new Review([
                'product_id' => $validated['product_id'],
                'review' => $validated['review'],
                'rating' => $validated['rating'],
                'status' => 'pending',
            ]);

            if (!$user->name || !$user->surname) {
                $review->name = $validated['name'] ?? null;
                $review->surname = $validated['surname'] ?? null;
            }

            $user->reviews()->save($review);
        } else {
            $review = Review::create([
                'product_id' => $validated['product_id'],
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'contact' => $validated['contact'], // Email or phone
                'review' => $validated['review'],
                'rating' => $validated['rating'],
                'status' => 'pending',
            ]);
        }

        return response()->json(['message' => 'Review submitted successfully, awaiting approval.']);
    }

    public function getReviews(Request $request, $productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->where('status', 'accepted') // Only get accepted reviews
            ->paginate(10); // Paginate the results, 10 reviews per page

        return response()->json($reviews);
    }
}
