<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function edit()
    {
        // Fetch all settings to display in the form
        $settings = Setting::all();
        return view('admin.pages.settings.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validate and update each setting in the database
        foreach ($request->except('_token') as $key => $value) {
            $this->settingsService->set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
