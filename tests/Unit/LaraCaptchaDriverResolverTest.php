<?php

namespace Martian\LaraCaptcha\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Martian\LaraCaptcha\LaraCaptcha;
use Martian\LaraCaptcha\Tests\TestCase;

class LaraCaptchaDriverResolverTest extends TestCase
{
    /**
     * @var array
     */
    protected $defaultDriver;

    /**
     * @var \Martian\LaraCaptcha\LaraCaptcha
     */
    protected $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultDriver = config('laracaptcha.default');
    }

    /**
     * @test it can get default driver
     */
    public function it_get_default_driver()
    {
        $this->assertEquals('recaptcha', $this->defaultDriver);
    }

    /**
     * @test it can get hcaptcha driver as default if set
     */
    public function it_get_hcaptcha_driver()
    {
       Config::set('laracaptcha.default', 'hcaptcha');
       $this->assertEquals('hcaptcha', config('laracaptcha.default'));
    }

    /**
     * @test it can get correct hcaptcha script when using hcaptcha driver
     */
    public function it_get_correct_hcaptcha_javascript_script()
    {
        Config::set('laracaptcha.default', 'hcaptcha');
        $this->driver = new LaraCaptcha();
        $this->assertEquals('<script src="https://js.hcaptcha.com/1/api.js?render=onload&hl=en&recaptchacompat=off" async defer></script>'."\n", $this->driver->script());
    }

    /**
     * @test it can get correct recaptcha script when using recaptcha driver
     */
    public function it_get_correct_recaptcha_javascript_script()
    {
        Config::set('laracaptcha.default', 'recaptcha');
        $this->driver = new LaraCaptcha();
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI&hl=en"></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get correct recaptcha invisible button when using correct version
     */
    public function it_get_correct_recaptcha_invisible_button()
    {
        Config::set('laracaptcha.default', 'recaptcha');
        Config::set('laracaptcha.drivers.recaptcha.version', 'v2');
        $this->driver = new LaraCaptcha();
        $button = '<button class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-callback="onSubmit">Submit</button>'."\n";
        $script = '<script>function onSubmit(){document.getElementById("myForm").submit();}</script>';
        $this->assertEquals($button . $script, $this->driver->displayInvisibleButton('myForm', 'Submit'));
    }
}