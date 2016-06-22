<?php

namespace Rikkei\Recruitment;

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
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if(!defined('RIKKEI_RECRUITMENT_PATH')) {
            define('RIKKEI_RECRUITMENT_PATH', __DIR__ . '/../');
        }
        $providers = [
            \Rikkei\Recruitment\Providers\RouteServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}
