<?php

namespace App\Http\Controllers\Bonus;

use App\Http\Controllers\Controller;
use App\Models\Bonus\Bonus;
use App\Models\Bonus\BonusSetting;
use App\Models\Bonus\UserProductView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiEarnBonusController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $bonusExecutions = $user->bonusExecutions()->with('bonus')->get();
        return response()->json($bonusExecutions);
    }

    public function getInfo(){
        $user = Auth::guard('api')->user();

        // Fetch the bonus setting for product views
        $productViewSetting = BonusSetting::where('type', 'product_view')->first();
        $registrationSetting = BonusSetting::where('type', 'registration')->first();
        $orderSetting = BonusSetting::where('type', 'order')->first();


        $viewCount = UserProductView::where('user_id', $user->id)->count();

        // Check if the last bonus was achieved today
        if ($user->last_product_view_bonus_achieved_at && Carbon::parse($user->last_product_view_bonus_achieved_at)->isToday()) {
            $viewCount = $productViewSetting->target_count;
        }

        $productViewInfo = [
            'title' => $productViewSetting->title,
            'description' => $productViewSetting->description,
            'bonus_amount' => (int)$productViewSetting->bonus_amount,
            'product_view_target' => $productViewSetting->target_count,
            'current_view_count' => $viewCount,
            'bonus_achieved' => $viewCount >= $productViewSetting->target_count,
        ];

        $registrationInfo = [
            'title' => $registrationSetting->title,
            'description' => $registrationSetting->description,
            'bonus_amount' => (int)$registrationSetting->bonus_amount,
            'bonus_achieved' => true,
        ];

        $orderInfo = [
            'title' => $orderSetting?->title,
            'description' => $orderSetting?->description,
        ];

        // Prepare the response (customize it based on your needs)
        return response()->json([
            'product_view' => $productViewInfo,
            'registration' => $registrationInfo,
            'order' => $orderInfo
        ]);
    }

}
