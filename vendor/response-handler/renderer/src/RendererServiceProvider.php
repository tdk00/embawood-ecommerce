<?php
namespace ResponseHandler;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class RendererServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ResponseFactoryContract::class, function ($app) {
            return new ArtResponseFactory(
                $app['view'],        // View Factory
                $app['redirect']     // Redirector
            );
        });
    }
}
