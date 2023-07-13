<?php

namespace Martian\LaraCaptcha\Tests\Unit\ReCaptcha;

use Illuminate\Support\Facades\Config;
use Martian\LaraCaptcha\Drivers\ReCaptcha\ReCaptchaV3;
use Martian\LaraCaptcha\Tests\TestCase;

class ReCaptchaV3DriverTest extends TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Martian\LaraCaptcha\Drivers\ReCaptcha\ReCaptchaV3
     */
    protected $driver;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('laracaptcha.drivers.recaptcha.version', 'v3');
        $this->config = config('laracaptcha.drivers.recaptcha');
        $this->driver = new ReCaptchaV3();
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
     * @test it can get site url
     */
    public function it_get_site_url()
    {
        $this->assertEquals('127.0.0.1', $this->config['site_url']);
    }

    /**
     * @test it can get correct version
     */
    public function it_get_correct_version()
    {
        $this->assertEquals('v3', $this->config['version']);
    }

    /**
     * @test it can get javascript script
     */
    public function it_get_javascript_script()
    {
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI&hl=en"></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get javascript script with language
     */
    public function it_get_javascript_script_with_language()
    {
        $this->app->setLocale('fr');
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI&hl=fr"></script>' . "\n", $this->driver->script());
    }

    /**
     * @test it can get display widget
     */
    public function it_get_display_widget()
    {
        $input = '<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-laracaptcha">';
        $script = '
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute("6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI", {action: "homepage"}).then(function(token) {
                    document.getElementById("g-recaptcha-response-laracaptcha").value = token;
                    
                });
            });
        </script>
        ';

        $this->assertEquals($input . "\n" . $script, $this->driver->display());
    }
}
