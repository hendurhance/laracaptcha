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

namespace Martian\LaraCaptcha\Tests\Unit\ReCaptcha;

use Illuminate\Support\Facades\Config;
use Martian\LaraCaptcha\Drivers\ReCaptcha\ReCaptchaV3;
use Martian\LaraCaptcha\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
     * It can get site key
     */
    #[Test]
    public function it_get_site_key()
    {
        $this->assertEquals('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', $this->config['site_key']);
    }

    /**
     * It can get secret key
     */
    #[Test]
    public function it_get_secret_key()
    {
        $this->assertEquals('6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', $this->config['secret_key']);
    }

    /**
     * It can get site url
     */
    #[Test]
    public function it_get_site_url()
    {
        $this->assertEquals('127.0.0.1', $this->config['site_url']);
    }

    /**
     * It can get correct version
     */
    #[Test]
    public function it_get_correct_version()
    {
        $this->assertEquals('v3', $this->config['version']);
    }

    /**
     * It can get javascript script
     */
    #[Test]
    public function it_get_javascript_script()
    {
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI&hl=en"></script>' . "\n", $this->driver->script());
    }

    /**
     * It can get javascript script with language
     */
    #[Test]
    public function it_get_javascript_script_with_language()
    {
        $this->app->setLocale('fr');
        $this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI&hl=fr"></script>' . "\n", $this->driver->script());
    }

    /**
     * It can get display widget
     */
    #[Test]
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
