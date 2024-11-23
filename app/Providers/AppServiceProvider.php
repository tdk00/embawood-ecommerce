<?php

namespace App\Providers;

use App\Services\BonusSettingsService;
use App\Services\CreditService;
use App\Services\SettingsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CreditService::class, function ($app) {
            return new CreditService();
        });

        $this->app->singleton(SettingsService::class, function ($app) {
            return new SettingsService();
        });

        $this->app->singleton(BonusSettingsService::class, function ($app) {
            return new BonusSettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
