<?php

namespace App\Http\Controllers\Bonus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBonusHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $bonusExecutions = $user->bonusExecutions()->with('bonus')->get();
        return response()->json($bonusExecutions);
    }
}
