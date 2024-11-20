<?php
namespace ViewManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as ViewFactoryContract;

class LayoutServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ViewFactoryContract::class, function ($app) {
            return new TempViewFactory(
                $app['view.engine.resolver'],
                $app['view.finder'],
                $app['events']
            );
        });
    }
}
