<?php

namespace App\Http\Controllers\Basket;

use App\Http\Controllers\Controller;
use App\Models\Basket\SafetyInformation;
use Illuminate\Http\Request;

class SafetyInformationController extends Controller
{
    public function index()
    {

        $safetyInformations = SafetyInformation::all();

        return response()->json([
            'status' => 'success',
            'data' => $safetyInformations,
        ]);
    }
}
