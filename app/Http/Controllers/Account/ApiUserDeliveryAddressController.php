<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserDeliveryAddressController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->user = Auth::guard('sanctum')->user();
    }

    public function index()
    {
        $addresses = $this->user->deliveryAddresses;
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'is_default' => 'boolean',
        ]);

        $address = $this->user->deliveryAddresses()->create($request->all());

        if ($request->is_default) {
            $this->user->deliveryAddresses()->update(['is_default' => false]);
            $address->is_default = true;
            $address->save();
        }

        return response()->json($address, 201);
    }

    public function update(Request $request, $id)
    {
        $address = UserDeliveryAddress::where('user_id', $this->user->id)->findOrFail($id);


        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'is_default' => 'boolean',
        ]);

        $address->update($request->all());

        if ($request->is_default) {
            $this->user->deliveryAddresses()->update(['is_default' => false]);
            $address->is_default = true;
            $address->save();
        }

        return response()->json($address);
    }

    public function destroy($id)
    {
        $address = UserDeliveryAddress::where('user_id', $this->user->id)->findOrFail($id);
        $address->delete();
        return response()->json(['message' => 'Address deleted']);
    }
}
