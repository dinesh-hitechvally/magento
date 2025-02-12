<?php

namespace Dinesh\Magento\App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Dinesh\Magento\Console\Commands\MakeModel;

class MagentoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        
        // Publish Routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php'); 

        // Publish Config
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('config.php'),
        ]);

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/../../Database/Migrations' => database_path('migrations'),
        ], 'migrations');

        // Publish seeders
        $this->publishes([
            __DIR__ . '/../../Database/Seeders/' => database_path('seeders'),
        ], 'seeders');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.magento.make-model', function ($app) {
            return new MakeModel();
        });

        $this->commands([
            'command.magento.make-model',
        ]);
        // Register config, commands, or bindings
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'magento');
    }
}





