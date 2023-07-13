<?php

namespace Martian\LaraCaptcha\Tests\Unit\ReCaptcha;

use Illuminate\Support\Facades\Config;
use Martian\LaraCaptcha\Drivers\ReCaptcha\ReCaptchaV2;
use Martian\LaraCaptcha\Tests\TestCase;

class ReCaptchaV2DriverTest extends TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Martian\LaraCaptcha\Drivers\ReCaptcha\ReCaptchaV2
     */
    protected $driver;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('laracaptcha.drivers.recaptcha.version', 'v2');
        $this->config = config('laracaptcha.drivers.recaptcha');
        $this->driver = new ReCaptchaV2();
    }

    /**
     * @test it can get site key
     */
    public function it_get_site_key()
    {
        $this->assertEquals('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', $this->config['site_key']);
    }

    /**
     * @test it can get secret key
     */
    public function it_get_secret_key()
    {
        $this->assertEquals('6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', $this->config['secret_key']);
    }

    /**
     * @test it can get script url
     */
    public function it_get_script_url()
    {
        $this->assertEquals('https://www.google.com/recaptcha/api.js', $this->config['script_url']);
    }

    /**
     * @test it can get verify url
     */
    public function it_get_verify_url()
    {
        $this->assertEquals('https://www.google.com/recaptcha/api/siteverify', $this->config['verify_url']);
    }

    /**
     * @test it can get correct version
     */
    public function it_get_correct_version()
    {
        $this->assertEquals('v2', $this->config['version']);
    }

    /**
     * @test it can get javascript script
     */
    public function it_get_javascript_script()
    {
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=onload&hl=en" async defer></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get javascript script with language
     */
    public function it_get_javascript_script_with_language()
    {
        $this->app->setLocale('fr');
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=onload&hl=fr" async defer></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get javascript script with render and callback
     */
    public function it_get_javascript_script_with_render_and_callback()
    {
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=en" async defer></script>' . "\n", $this->driver->script('onloadCallback', true));
    }

    /**
     * @test it can get display widget
     */
    public function it_get_display_widget()
    {
        $this->assertEquals('<div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>', $this->driver->display());
    }

    /**
     * @test it can get display widget with attributes
     */
    public function it_get_display_widget_with_attributes()
    {
        $this->assertEquals('<div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-theme="dark" data-size="compact"></div>', $this->driver->display(['data-theme' => 'dark', 'data-size' => 'compact']));
    }

    /**
     * @test it can get invisible button widget
     */
    public function it_get_invisible_button_widget()
    {
        $this->assertEquals('<button class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-callback="onSubmit">Submit</button>' . "\n", $this->driver->displayInvisibleButton('myForm', 'Submit', ['data-callback' => 'onSubmit']));
    }
}
