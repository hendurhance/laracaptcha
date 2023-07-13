<?php

namespace Martian\LaraCaptcha\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Martian\LaraCaptcha\LaraCaptcha;
use Illuminate\Support\Facades\Validator;

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
        }

        // hCaptcha
        Validator::extend(config('laracaptcha.validation_rule.hcaptcha'), function ($attribute, $value, $parameters, $validator) {
            return (new LaraCaptcha())->validate($value, request()->ip());
        }, config('laracaptcha.error_message'));

        // reCaptcha v2 & v3
        Validator::extend(config('laracaptcha.validation_rule.recaptcha'), function ($attribute, $value, $parameters, $validator) {
            return (new LaraCaptcha())->validate($value, request()->ip());
        }, config('laracaptcha.error_message'));
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
