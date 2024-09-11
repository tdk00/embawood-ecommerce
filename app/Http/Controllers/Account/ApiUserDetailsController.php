<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserDetailsController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->user = Auth::guard('sanctum')->user();
    }

    public function getUserDetails()
    {
        $details = [
            'id' => $this->user->name,
            'surname' => $this->user->surname,
            'phone' => $this->user->phone,
            'birthdate' => $this->user->birthdate
        ];
        return response()->json($details);
    }

}
