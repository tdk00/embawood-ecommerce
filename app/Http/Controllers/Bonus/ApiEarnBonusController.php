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

    /**
     * @OA\Get(
     *     path="/api/earn-bonus-info",
     *     operationId="getEarnBonusInfo",
     *     tags={"Bonuses"},
     *     summary="Retrieve bonus earning information",
     *     description="Provides information about available bonuses for product views, registration, and orders, including current progress and bonus amounts.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Bonus earning information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="product_view",
     *                 type="object",
     *                 description="Details about the product view bonus",
     *                 @OA\Property(property="title", type="string", description="Title of the bonus", example="Earn bonus for viewing products"),
     *                 @OA\Property(property="description", type="string", description="Description of the bonus", example="View products to earn bonuses."),
     *                 @OA\Property(property="bonus_amount", type="integer", description="Bonus amount for achieving the target", example=10),
     *                 @OA\Property(property="product_view_target", type="integer", description="Target product views to achieve the bonus", example=5),
     *                 @OA\Property(property="current_view_count", type="integer", description="Current number of views by the user", example=3),
     *                 @OA\Property(property="bonus_achieved", type="boolean", description="Indicates if the user has achieved the bonus", example=false)
     *             ),
     *             @OA\Property(
     *                 property="registration",
     *                 type="object",
     *                 description="Details about the registration bonus",
     *                 @OA\Property(property="title", type="string", description="Title of the bonus", example="Registration Bonus"),
     *                 @OA\Property(property="description", type="string", description="Description of the bonus", example="Earn bonus for registering in the app."),
     *                 @OA\Property(property="bonus_amount", type="integer", description="Bonus amount for registration", example=20),
     *                 @OA\Property(property="bonus_achieved", type="boolean", description="Indicates if the registration bonus has been achieved", example=true)
     *             ),
     *             @OA\Property(
     *                 property="order",
     *                 type="object",
     *                 description="Details about the order bonus",
     *                 @OA\Property(property="title", type="string", nullable=true, description="Title of the bonus", example="Order Bonus"),
     *                 @OA\Property(property="description", type="string", nullable=true, description="Description of the bonus", example="Earn bonus for placing orders.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="User not authenticated")
     *         )
     *     )
     * )
     */
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
