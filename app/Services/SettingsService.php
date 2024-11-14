<?php
namespace App\Services;

use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected $cacheKey = 'app_settings';

    public function __construct()
    {
        // Initialize default settings when the service is instantiated
        $this->initializeDefaults();
    }

    public function get($key, $default = null)
    {
        $settings = $this->all();
        return $settings[$key] ?? $default;
    }

    public function set($key, $value)
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        $this->clearCache();
    }

    public function all()
    {
        return Cache::rememberForever($this->cacheKey, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function clearCache()
    {
        Cache::forget($this->cacheKey);
    }

    // Method to initialize default settings
    public function initializeDefaults()
    {
        $defaultSettings = [
            'show_bonus_in_app' => 'false',
            'whatsapp_number' => '1234567890', // Default value as a string (can also be "true")
        ];

        Setting::firstOrCreate(
            ['key' => 'show_bonus_in_app'],
            ['value' => 'false', 'type' => 'boolean']
        );

        Setting::firstOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => '1234567890', 'type' => 'text']
        );

        // Clear cache to ensure defaults are immediately available
        $this->clearCache();
    }
}
