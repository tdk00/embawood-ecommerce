<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/settings",
     *     operationId="getApplicationSettings",
     *     tags={"Settings"},
     *     summary="Retrieve application settings",
     *     description="Returns key application settings, such as whether bonuses are displayed in the app and the WhatsApp contact number.",
     *     @OA\Response(
     *         response=200,
     *         description="Settings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="show_bonus_in_app", type="boolean", description="Indicates if bonuses are displayed in the app", example=true),
     *             @OA\Property(property="whatsapp_number", type="string", description="WhatsApp contact number", example="+123456789")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        // Retrieve all settings as key-value pairs
        $settings = Setting::pluck('value', 'key')->toArray();

        $formattedSettings = [
            'show_bonus_in_app' => filter_var($settings['show_bonus_in_app'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'whatsapp_number' => $settings['whatsapp_number'] ?? '',
        ];

        return response()->json($formattedSettings);
    }
}
