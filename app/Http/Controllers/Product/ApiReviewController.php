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

        if (!$user ) {
            $rules['name'] = 'required|string';
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

            if ( $user->name ) {
                $review->name = $user?->name ?? null;
            }

            $user->reviews()->save($review);
        } else {
            $review = Review::create([
                'product_id' => $validated['product_id'],
                'name' => $validated['name'],
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
        // Fetch only accepted reviews for the given product
        $reviews = Review::where('product_id', $productId)
            ->where('status', 'accepted')
            ->get();

        // Calculate the number of reviews
        $reviewCount = $reviews->count();

        // Calculate the average rating
        $averageRating = $reviews->avg('rating');

        // Return the structured response with review count, average rating, and reviews
        return response()->json([
            'review_count' => $reviewCount,
            'average_rating' => $averageRating,
            'reviews' => $reviews,
        ]);
    }
}
