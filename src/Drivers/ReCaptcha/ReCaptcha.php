<?php

namespace Martian\LaraCaptcha\Drivers\ReCaptcha;

use Martian\LaraCaptcha\Contracts\DisplayInvisibleButtonInterface;
use Martian\LaraCaptcha\Contracts\DriverInterface;
use Martian\LaraCaptcha\Exceptions\UnsupportedVersionException;

class ReCaptcha implements DriverInterface, DisplayInvisibleButtonInterface
{
    /**
     * reCaptcha Version
     * 
     * @var string
     */
    protected string $version;

    /**
     * reCaptcha Driver
     * 
     * @var DriverInterface
     */
    protected DriverInterface $versionDriver;

    /**
     * reCaptcha constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->version = config('laracaptcha.drivers.recaptcha.version');
        $this->versionDriver = $this->getVersionDriver();
    }

    /**
     * Get Driver
     * 
     * @return DriverInterface
     */
    public function getVersionDriver(): DriverInterface
    {
        switch ($this->version) {
            case 'v2':
                return new ReCaptchaV2();
                break;
            case 'v3':
                return new ReCaptchaV3();
                break;
            default:
                throw new UnsupportedVersionException("Unsupported reCaptcha version: {$this->version}, supported versions are: v2, v3");
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
        return $this->versionDriver->display($attributes);
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
        return $this->versionDriver->script($onload, $render, $locale, $recaptchaCompat);
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
        return $this->versionDriver->validate($res, $ipAddress);
    }

    /**
     * Display invisible captcha button.
     * 
     * @param string $formId Form identifier.
     * @param string $label Button label.
     * @param array $attributes
     * @return string
     */
    public function displayInvisibleButton(string $formId = null, ?string $label, array $attributes = []): string
    {
        if ($this->version === 'v3') {
            throw new UnsupportedVersionException("Unsupported reCaptcha version: {$this->version}, supported versions are: v2");
        }

        return $this->versionDriver->displayInvisibleButton($formId, $label, $attributes);
    }
}
