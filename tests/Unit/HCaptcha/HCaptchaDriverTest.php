<?php

namespace Martian\LaraCaptcha\Tests\Unit\HCaptcha;

use Martian\LaraCaptcha\Drivers\HCaptcha\HCaptcha;
use Martian\LaraCaptcha\Tests\TestCase;

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
     * @test it can get site key
     */
    public function it_get_site_key()
    {
        $this->assertEquals('10000000-ffff-ffff-ffff-000000000001', $this->config['site_key']);
    }

    /**
     * @test it can get secret key
     */
    public function it_get_secret_key()
    {
        $this->assertEquals('0x0000000000000000000000000000000000000000', $this->config['secret_key']);
    }

    /**
     * @test it can get script url
     */
    public function it_get_script_url()
    {
        $this->assertEquals('https://js.hcaptcha.com/1/api.js', $this->config['script_url']);
    }

    /**
     * @test it can get verify url
     */
    public function it_get_verify_url()
    {
        $this->assertEquals('https://hcaptcha.com/siteverify', $this->config['verify_url']);
    }

    /**
     * @test it can get javascript script
     */
    public function it_get_javascript_script()
    {
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=en&recaptchacompat=off" async defer></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get javascript script with language
     */
    public function it_get_javascript_script_with_language()
    {
        $this->app->setLocale('fr');
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=fr&recaptchacompat=off" async defer></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get javascript script with recaptcha compat on
     */
    public function it_get_javascript_script_with_recaptcha_compat_on()
    {
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=en&recaptchacompat=on" async defer></script>' . "\n", $this->driver->script(null, false, 'en', 'on'));
    }

    /**
     * @test it can get javascript script with callback function
     */
    public function it_get_javascript_script_with_callback_function()
    {
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?onload=callbackFunction&render=onload&hl=en&recaptchacompat=off" async defer></script>' . "\n", $this->driver->script('callbackFunction'));
    }

    /**
     * @test it can get display widget
     */
    public function it_get_display_widget()
    {
        $this->assertEquals('<div class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001"></div>', $this->driver->display());
    }

    /**
     * @test it can get display widget with attributes
     */
    public function it_get_display_widget_with_attributes()
    {
        $this->assertEquals('<div data-theme="dark" data-size="compact" class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001"></div>', $this->driver->display(['data-theme' => 'dark', 'data-size' => 'compact']));
    }

    /**
     * @test it can get invisible button widget
     */
    public function it_get_invisible_button_widget()
    {
        $this->assertEquals('<button data-callback="callbackFunction" class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001">Submit</button>' . "\n", $this->driver->displayInvisibleButton('myFormId', 'Submit', ['data-callback' => 'callbackFunction']));
    }

    /**
     * @test it can validate captcha token
     */
    public function it_validate_captcha_token()
    {
        $this->assertTrue($this->driver->validate('10000000-aaaa-bbbb-cccc-000000000001', request()->ip()));
    }
}
