<?php

namespace Martian\LaraCaptcha\Tests;

use Martian\LaraCaptcha\LaraCaptcha;
use Orchestra\Testbench\TestCase as Orchestra;
use Martian\LaraCaptcha\Providers\LaraCaptchaServiceProvider;

class TestCase extends Orchestra
{
    /**
     * Load package service provider
     * 
     * @param  \Illuminate\Foundation\Application $app
     * @return Martian\LaraCaptcha\LaraCaptchaServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [LaraCaptchaServiceProvider::class];
    }

    /**
     * Load package alias
     * 
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'LaraCaptcha' => LaraCaptcha::class,
        ];
    }
}