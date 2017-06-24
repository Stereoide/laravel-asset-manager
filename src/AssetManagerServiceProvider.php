<?php

namespace Stereoide\AssetManager;

use Illuminate\Support\ServiceProvider;

class AssetManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Config file */

        $this->publishes([
            __DIR__ . '/config/assetmanager.php' => config_path('assetmanager.php'),
        ], 'config');

        /* Migrations */

        $this->publishes([
            __DIR__ . '/migrations/' => database_path('migrations')
        ], 'migrations');

        /* Routes */

        include __DIR__ . '/routes/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \App::bind('assetmanager', function () {
            return new \Stereoide\AssetManager\AssetManagerController;
        });
    }
}
