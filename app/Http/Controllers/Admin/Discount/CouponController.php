<?php
namespace App\Http\Controllers\Admin\Discount;
use App\Http\Controllers\Controller;
use App\Models\Discount\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.pages.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'discount_percentage' => 'required|numeric|between:0,100',
            'usage_limit' => 'nullable|integer|min:0',
            'min_required_amount' => 'nullable|numeric|min:0',
            'max_required_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        Coupon::create([
            'code' => $request->input('code'),
            'discount_percentage' => $request->input('discount_percentage'),
            'description' => "",
            'usage_limit' => $request->input('usage_limit'),
            'min_required_amount' => $request->input('min_required_amount'),
            'max_required_amount' => $request->input('max_required_amount'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'is_active' => $request->input('is_active'),
            'usage_count' => 0,  // Start with zero usage count
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.pages.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'discount_percentage' => 'required|numeric|between:0,100',
            'usage_limit' => 'nullable|integer|min:0',
            'min_required_amount' => 'nullable|numeric|min:0',
            'max_required_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $coupon->update([
            'code' => $request->input('code'),
            'description' => "",
            'discount_percentage' => $request->input('discount_percentage'),
            'usage_limit' => $request->input('usage_limit'),
            'min_required_amount' => $request->input('min_required_amount'),
            'max_required_amount' => $request->input('max_required_amount'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'is_active' => $request->input('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
