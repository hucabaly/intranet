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
        if(!defined('RIKKEI_CORE_PATH')) {
            define('RIKKEI_CORE_PATH', __DIR__ . '/../');
        }
        
        $providers = [
            \Rikkei\Core\Providers\AuthServiceProvider::class,
            \Rikkei\Core\Providers\EventServiceProvider::class,
            \Rikkei\Core\Providers\RouteServiceProvider::class,
            \Rikkei\Core\Providers\ThemeServiceProvider::class,
            \Rikkei\Core\Providers\DatabaseServiceProvider::class,
            \Rikkei\Core\Providers\SessionServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}