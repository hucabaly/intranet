<?php

namespace Rikkei\Core;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'core');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $providers = [
            \Rikkei\Core\Providers\AuthServiceProvider::class,
            \Rikkei\Core\Providers\EventServiceProvider::class,
            \Rikkei\Core\Providers\RouteServiceProvider::class,
            \Rikkei\Core\Providers\ThemeServiceProvider::class,
            \Rikkei\Core\Providers\DatabaseServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}