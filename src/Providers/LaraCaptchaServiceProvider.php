<?php

namespace Martian\LaraCaptcha\Providers;

use Illuminate\Support\ServiceProvider;
use Martian\LaraCaptcha\LaraCaptcha;

class LaraCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laracaptcha');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laracaptcha');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/laracaptcha.php' => config_path('laracaptcha.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laracaptcha'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laracaptcha'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laracaptcha'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/laracaptcha.php', 'laracaptcha');

        // Register the main class to use with the facade
        $this->app->singleton('laracaptcha', function () {
            return new LaraCaptcha;
        });
    }
}
