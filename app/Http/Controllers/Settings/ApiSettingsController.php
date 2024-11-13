<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    public function index(): JsonResponse
    {
        // Retrieve all settings as key-value pairs
        $settings = Setting::pluck('value', 'key')->toArray();

        // Convert any values to appropriate types, e.g., boolean
        $formattedSettings = [
            'show_bonus_in_app' => filter_var($settings['show_bonus_in_app'] ?? false, FILTER_VALIDATE_BOOLEAN),
            // Add more settings as needed
        ];

        return response()->json($formattedSettings);
    }
}
