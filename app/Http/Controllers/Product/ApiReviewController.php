<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiReviewController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reviews/store",
     *     operationId="storeReview",
     *     tags={"Reviews"},
     *     summary="Submit a review for a product",
     *     description="Allows a user to submit a review for a product. If authenticated, the user's name will be used. Otherwise, name and contact information are required.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="product_id", type="integer", description="ID of the product being reviewed", example=123),
     *             @OA\Property(property="review", type="string", description="Review text", example="Great product!"),
     *             @OA\Property(property="rating", type="integer", description="Rating out of 5", example=5),
     *             @OA\Property(property="name", type="string", description="Reviewer name (required for guest users)", example="John Doe"),
     *             @OA\Property(property="contact", type="string", description="Reviewer contact info (email or phone, required for guest users)", example="johndoe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review submitted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Success message", example="Review submitted successfully, awaiting approval.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Validation error message", example="The review field is required.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/reviews/{productId}",
     *     operationId="getProductReviews",
     *     tags={"Reviews"},
     *     summary="Retrieve reviews for a product",
     *     description="Fetches all accepted reviews for a given product along with the review count and average rating.",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID of the product to fetch reviews for",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="review_count", type="integer", description="Total number of reviews", example=10),
     *             @OA\Property(property="average_rating", type="number", format="float", description="Average rating for the product", example=4.5),
     *             @OA\Property(
     *                 property="reviews",
     *                 type="array",
     *                 description="List of reviews",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Review ID", example=1),
     *                     @OA\Property(property="product_id", type="integer", description="ID of the reviewed product", example=123),
     *                     @OA\Property(property="name", type="string", description="Name of the reviewer", example="John Doe"),
     *                     @OA\Property(property="rating", type="integer", description="Rating out of 5", example=5),
     *                     @OA\Property(property="review", type="string", description="Review text", example="Great product!"),
     *                     @OA\Property(property="status", type="string", description="Status of the review", example="accepted"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time the review was created", example="2024-01-01T10:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found or no reviews available",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="Product not found")
     *         )
     *     )
     * )
     */
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
