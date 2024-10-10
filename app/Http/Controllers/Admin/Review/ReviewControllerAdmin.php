<?php
namespace App\Http\Controllers\Admin\Review;
use App\Http\Controllers\Controller;
use App\Models\Consultation\CallRequest;
use App\Models\Product\Review;
use Illuminate\Http\Request;

class ReviewControllerAdmin extends Controller
{
    public function index()
    {
        // Fetch paginated reviews
        $reviews = Review::with(['product', 'user'])
            ->whereNotNull('user_id')
            ->orderBy('id', 'desc')
            ->paginate(10); // Adjust the number of reviews per page

        return view('admin.pages.reviews.index', compact('reviews'));
    }

    public function data(Request $request)
    {
        $columns = [
            'products.name', // product_name
            'users.name', // user_name
            'users.phone', // user_phone
            'review',
            'rating',
            'status',
        ];

        $query = Review::select('reviews.*')
            ->join('products', 'products.id', '=', 'reviews.product_id')
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->whereNotNull('reviews.user_id');

        // Apply search if needed
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search) {
                $query->where('products.name', 'LIKE', "%$search%")
                    ->orWhere('users.name', 'LIKE', "%$search%")
                    ->orWhere('users.phone', 'LIKE', "%$search%")
                    ->orWhere('reviews.review', 'LIKE', "%$search%");
            });
        }

        // Sorting
        $orderByColumn = $columns[$request->input('order.0.column')];
        $orderByDirection = $request->input('order.0.dir');
        $query->orderBy($orderByColumn, $orderByDirection);

        // Paginate the result
        $reviewsPaginated = $query->paginate(
            $request->input('length'),
            ['*'],
            'page',
            ($request->input('start') / $request->input('length')) + 1
        );

        // Prepare the data for DataTables
        $data = [];
        foreach ($reviewsPaginated as $review) {
            $data[] = [
                'product_name' => $review->product->name,
                'user_name' => $review->user->name,
                'user_phone' => $review->user->phone,
                'review' => $review->review,
                'rating' => $review->rating,
                'status' => ucfirst($review->status),
                'action' => '<form action="' . route('admin.reviews.updateStatus', $review->id) . '" method="POST">' .
                    csrf_field() .
                    method_field('PUT') .
                    '<select name="status" class="form-select mb-2" onchange="this.form.submit()">' .
                    '<option value="pending"' . ($review->status == 'pending' ? ' selected' : '') . '>Pending</option>' .
                    '<option value="accepted"' . ($review->status == 'accepted' ? ' selected' : '') . '>Accepted</option>' .
                    '<option value="rejected"' . ($review->status == 'rejected' ? ' selected' : '') . '>Rejected</option>' .
                    '</select>' .
                    '</form>'
            ];
        }

        // Send the response in the format DataTables expects
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => Review::whereNotNull('user_id')->count(),
            'recordsFiltered' => $reviewsPaginated->total(),
            'data' => $data,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validate the status change request
        $validated = $request->validate([
            'status' => 'required|in:accepted,pending,rejected',
        ]);

        // Find the review by ID
        $review = Review::findOrFail($id);

        // Update the status
        $review->update(['status' => $validated['status']]);

        return redirect()->route('admin.reviews.index')->with('success', 'Yeniləndi');
    }
}
