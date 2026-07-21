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

namespace Martian\LaraCaptcha\Tests\Unit\HCaptcha;

use Martian\LaraCaptcha\Drivers\HCaptcha\HCaptcha;
use Martian\LaraCaptcha\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HCaptchaDriverTest extends TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Martian\LaraCaptcha\Drivers\HCaptcha\HCaptcha
     */
    protected $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = config('laracaptcha.drivers.hcaptcha');
        $this->driver = new HCaptcha();
    }

    /**
     * It can get site key
     */
    #[Test]
    public function it_get_site_key()
    {
        $this->assertEquals('10000000-ffff-ffff-ffff-000000000001', $this->config['site_key']);
    }

    /**
     * It can get secret key
     */
    #[Test]
    public function it_get_secret_key()
    {
        $this->assertEquals('0x0000000000000000000000000000000000000000', $this->config['secret_key']);
    }

    /**
     * It can get script url
     */
    #[Test]
    public function it_get_script_url()
    {
        $this->assertEquals('https://js.hcaptcha.com/1/api.js', $this->config['script_url']);
    }

    /**
     * It can get verify url
     */
    #[Test]
    public function it_get_verify_url()
    {
        $this->assertEquals('https://hcaptcha.com/siteverify', $this->config['verify_url']);
    }

    /**
     * It can get javascript script
     */
    #[Test]
    public function it_get_javascript_script()
    {
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=en&recaptchacompat=off" async defer></script>' . "\n", $this->driver->script());
    }

    /**
     * It can get javascript script with language
     */
    #[Test]
    public function it_get_javascript_script_with_language()
    {
        $this->app->setLocale('fr');
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=fr&recaptchacompat=off" async defer></script>' . "\n", $this->driver->script());
    }

    /**
     * It can get javascript script with recaptcha compat on
     */
    #[Test]
    public function it_get_javascript_script_with_recaptcha_compat_on()
    {
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=en&recaptchacompat=on" async defer></script>' . "\n", $this->driver->script(null, false, 'en', 'on'));
    }

    /**
     * It can get javascript script with callback function
     */
    #[Test]
    public function it_get_javascript_script_with_callback_function()
    {
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?onload=callbackFunction&render=onload&hl=en&recaptchacompat=off" async defer></script>' . "\n", $this->driver->script('callbackFunction'));
    }

    /**
     * It can get display widget
     */
    #[Test]
    public function it_get_display_widget()
    {
        $this->assertEquals('<div class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001"></div>', $this->driver->display());
    }

    /**
     * It can get display widget with attributes
     */
    #[Test]
    public function it_get_display_widget_with_attributes()
    {
        $this->assertEquals('<div data-theme="dark" data-size="compact" class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001"></div>', $this->driver->display(['data-theme' => 'dark', 'data-size' => 'compact']));
    }

    /**
     * It can get invisible button widget
     */
    #[Test]
    public function it_get_invisible_button_widget()
    {
        $this->assertEquals('<button data-callback="callbackFunction" class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001">Submit</button>' . "\n", $this->driver->displayInvisibleButton('myFormId', 'Submit', ['data-callback' => 'callbackFunction']));
    }

    /**
     * It can validate captcha token
     */
    #[Test]
    public function it_validate_captcha_token()
    {
        $this->assertTrue($this->driver->validate('10000000-aaaa-bbbb-cccc-000000000001', request()->ip()));
    }
}
