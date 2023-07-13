<?php

namespace Martian\LaraCaptcha\Providers;

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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/laracaptcha.php' => config_path('laracaptcha.php'),
            ], 'config');
        }

        $this->extendValidationRules();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/laracaptcha.php', 'laracaptcha');

        // Register the main class to use with the facade
        $this->app->singleton('laracaptcha', function () {
            return new LaraCaptcha;
        });
    }

    /**
     * Extend Captcha Validation Rule
     * 
     * @return void
     */
    protected function extendValidationRules()
    {
        $this->extendHCaptchaValidationRule();
        $this->extendReCaptchaValidationRules();
    }

    /**
     * Extend hCaptcha Validation Rule
     * 
     * @return void
     */
    protected function extendHCaptchaValidationRule()
    {
        Validator::extend(config('laracaptcha.validation_rule.hcaptcha'), function ($attribute, $value, $parameters, $validator) {
            return (new LaraCaptcha())->validate($value, request()->ip());
        }, config('laracaptcha.error_message'));
    }

    /**
     * Extend reCaptcha Validation Rules
     * 
     * @return void
     */
    protected function extendReCaptchaValidationRules()
    {
        Validator::extend(config('laracaptcha.validation_rule.recaptcha'), function ($attribute, $value, $parameters, $validator) {
            return (new LaraCaptcha())->validate($value, request()->ip());
        }, config('laracaptcha.error_message'));
    }
}
