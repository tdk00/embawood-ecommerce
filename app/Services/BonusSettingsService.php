<?php
namespace App\Services;

use App\Models\Bonus\BonusSetting;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;

class BonusSettingsService
{
    public function __construct()
    {
        $this->initializeDefaults();
    }

    // Method to initialize default settings
    public function initializeDefaults()
    {
        $defaults = [
            [
                'title' => 'Məhsullara baxın',
                'description' => 'Gün ərzində 6 baxış üçün hər baxışa 5 bonus qazan.',
                'type' => 'product_view', // Type of bonus
                'target_count' => 5, // Number of views
                'bonus_amount' => 30.00, // Bonus amount
                'period' => 'daily', // Period duration
                'translations' => [
                    'az' => [
                        'title' => 'Məhsullara baxın',
                        'description' => 'Gün ərzində 6 baxış üçün hər baxışa 5 bonus qazan.',
                    ],
                    'en' => [
                        'title' => 'View Products',
                        'description' => 'Earn 5 bonus points for every 6 views daily.',
                    ],
                    'ru' => [
                        'title' => 'Просматривайте продукты',
                        'description' => 'Получайте 5 бонусов за каждые 6 просмотров в день.',
                    ],
                ],
            ],
            [
                'title' => 'Hesab yaradın',
                'description' => 'Sadəcə 1 dəfə üçün istifadəçi başına.',
                'type' => 'registration', // Type of bonus
                'target_count' => 1, // One-time registration
                'bonus_amount' => 200.00, // Bonus amount
                'period' => 'daily', // Period duration
                'translations' => [
                    'az' => [
                        'title' => 'Hesab yaradın',
                        'description' => 'Sadəcə 1 dəfə üçün istifadəçi başına.',
                    ],
                    'en' => [
                        'title' => 'Create an Account',
                        'description' => 'One-time bonus for new user registration.',
                    ],
                    'ru' => [
                        'title' => 'Создайте аккаунт',
                        'description' => 'Разовый бонус за регистрацию нового пользователя.',
                    ],
                ],
            ],
            [
                'title' => 'Sifarişə görə',
                'description' => 'Hər xərclənən 1 ₼ 1 bonus olaraq geri dönəcək. Limit yoxdur.',
                'type' => 'order', // Type of bonus
                'target_count' => 1, // Per order
                'bonus_amount' => 1.00, // Bonus amount
                'period' => 'daily', // Period duration
                'translations' => [
                    'az' => [
                        'title' => 'Sifarişə görə',
                        'description' => 'Hər xərclənən 1 ₼ 1 bonus olaraq geri dönəcək. Limit yoxdur.',
                    ],
                    'en' => [
                        'title' => 'Per Order',
                        'description' => 'Earn 1 bonus point for every 1 ₼ spent. No limits.',
                    ],
                    'ru' => [
                        'title' => 'За заказ',
                        'description' => 'Получайте 1 бонус за каждые 1 ₼ потраченные. Без ограничений.',
                    ],
                ],
            ],
        ];

        foreach ($defaults as $default) {
            $bonusSetting = BonusSetting::firstOrCreate(
                [
                    'type' => $default['type'], // Check only the 'type' column
                ],
                [
                    'title' => $default['title'],
                    'description' => $default['description'],
                    'target_count' => $default['target_count'],
                    'bonus_amount' => $default['bonus_amount'],
                    'period' => $default['period'],
                ]
            );

            if ($bonusSetting->wasRecentlyCreated) {
                foreach ($default['translations'] as $locale => $translation) {
                    $bonusSetting->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'title' => $translation['title'],
                            'description' => $translation['description'],
                        ]
                    );
                }
            }
        }
    }
}
