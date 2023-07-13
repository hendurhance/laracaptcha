<?php

namespace Martian\LaraCaptcha;

use Martian\LaraCaptcha\Contracts\DisplayInvisibleButtonInterface;
use Martian\LaraCaptcha\Contracts\DriverInterface;
use Martian\LaraCaptcha\Drivers\HCaptcha\HCaptcha;
use Martian\LaraCaptcha\Drivers\ReCaptcha\ReCaptcha;
use Martian\LaraCaptcha\Exceptions\UnsupportedVersionException;

class LaraCaptcha implements DriverInterface, DisplayInvisibleButtonInterface
{
    /**
     * LaraCaptcha Default Driver
     * 
     * @var string
     */
    protected string $defaultDriver;

    /**
     * LaraCaptcha Driver
     * 
     * @var DriverInterface
     */
    protected DriverInterface $driver;

    /**
     * LaraCaptcha constructor.
     * 
     */
    public function __construct()
    {
        $this->defaultDriver = config('laracaptcha.default');
        $this->driver = $this->getDriver();
    }

    /**
     * Get Driver
     * 
     * @return DriverInterface
     */
    public function getDriver(): DriverInterface
    {
        switch ($this->defaultDriver) {
            case 'hcaptcha':
                return new HCaptcha();
                break;
            case 'recaptcha':
                return new ReCaptcha();
                break;
            default:
                throw new UnsupportedVersionException("Unsupported LaraCaptcha version: {$this->defaultDriver}, supported versions are: hcaptcha, recaptcha");
        }
    }

    /**
     * Display captcha challenge.
     * 
     * @param array $attributes
     * @return string
     */
    public function display(array $attributes = []): string
    {
        return $this->driver->display($attributes);
    }

    /**
     * Display invisible captcha button.
     * 
     * @param string $formId Form identifier.
     * @param ?string $label Button label.
     * @param array $attributes
     * @return string
     * 
     */
    public function displayInvisibleButton(string $formId = null, ?string $label, array $attributes = []): string
    {
        return $this->driver->displayInvisibleButton($formId, $label, $attributes);
    }

    /**
     * Display captcha javascript.
     * 
     * @param ?string $onload Callback function name.
     * @param ?bool $render Render parameter (explicit|onload)
     * @param ?string $locale Language code.
     * @param ?string $recaptchaCompat Compatibility mode (on|off).
     * @return string
     */
    public function script(?string $onload = null, bool $render = false, ?string $locale = null, ?string $recaptchaCompat = null): string
    {
        return $this->driver->script($onload, $render, $locale, $recaptchaCompat);
    }

    /**
     * Validate captcha challenge.
     * 
     * @param $res
     * @param string $ipAddress
     * @return bool
     */
    public function validate($res, string $ipAddress = null): bool
    {
        if(empty($res)) {
            return false;
        }
        return $this->driver->validate($res, $ipAddress);
    }
}
