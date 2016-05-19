<?php

namespace Rikkei\Core\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            base_path('/vendor/almasaeed2010/adminlte/') => public_path('adminlte/'),
            app_path('/../config/menu/') => config_path(),
        ], 'assets');
        
        $this->publishes([
            app_path('/../config/menu.php') => config_path('/menu.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}