<?php

/**
 * Copyright (c) Josiah Endurance
 * 
 * @package LaraCaptcha
 * @license MIT License
 * 
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @since 1.0.0
 * @see https://github.com/hendurhance/laracaptcha
 */

namespace Martian\LaraCaptcha\Tests;

use Martian\LaraCaptcha\LaraCaptcha;
use Orchestra\Testbench\TestCase as Orchestra;
use Martian\LaraCaptcha\Providers\LaraCaptchaServiceProvider;
use Dotenv\Dotenv;

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

    /**
     * Setup the test environment.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // Load testing environment variables before parent setup
        $envFile = __DIR__ . '/../.env.testing';
        if (file_exists($envFile)) {
            $dotenv = Dotenv::createImmutable(dirname($envFile), '.env.testing');
            $dotenv->load();
        }

        parent::setUp();
    }
}