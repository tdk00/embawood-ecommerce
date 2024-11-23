<?php

namespace App\Http\Controllers\Admin\Bonus;

use App\Http\Controllers\Controller;
use App\Models\Bonus\BonusSetting;
use App\Models\Bonus\BonusSettingTranslation;
use App\Services\BonusSettingsService;
use Illuminate\Http\Request;

class BonusSettingController extends Controller
{
    protected $bonusSettingsService;
    public function __construct(BonusSettingsService $bonusSettingsService)
    {
        $this->bonusSettingsService = $bonusSettingsService;
    }
    public function editAll()
    {
        // Load the three predefined bonus settings
        $bonusSettings = BonusSetting::whereIn('type', ['product_view', 'registration', 'order'])->with('translations')->get();

        // Pass the bonus settings to the view
        return view('admin.pages.bonus-settings.edit-all', compact('bonusSettings'));
    }

    public function updateAll(Request $request)
    {
        // Validate input for all three types
        $rules = [
            'bonus_amount.*' => 'required|numeric',
            'title_az.*' => 'required|string',
            'title_en.*' => 'required|string',
            'title_ru.*' => 'required|string',
            'description_az.*' => 'nullable|string',
            'description_en.*' => 'nullable|string',
            'description_ru.*' => 'nullable|string',
        ];

        $request->validate($rules);

        // Loop through the bonus settings and update them
        foreach (['product_view', 'registration', 'order'] as $type) {
            $bonusSetting = BonusSetting::where('type', $type)->firstOrFail();
            $bonusSetting->bonus_amount = $request->input("bonus_amount.$type");

            if ($type == 'product_view') {
                $bonusSetting->target_count = $request->input("target_count.$type");
                $bonusSetting->period = $request->input("period.$type");
            }

            // Update translations manually for az, en, and ru
            foreach (['az', 'en', 'ru'] as $locale) {
                $translation = BonusSettingTranslation::where('bonus_setting_id', $bonusSetting->id)
                    ->where('locale', $locale)
                    ->first();

                if (!$translation) {
                    // Create new translation if not found
                    $translation = new BonusSettingTranslation();
                    $translation->bonus_setting_id = $bonusSetting->id;
                    $translation->locale = $locale;
                }

                // Update title and description for each language
                $translation->title = $request->input("title_{$locale}.$type");
                $translation->description = $request->input("description_{$locale}.$type");
                $translation->save();
            }

            $bonusSetting->save();
        }

//        return redirect()->route('admin.bonus-settings.editAll')->with('success', 'Bonus settings updated successfully.');
    }
}
