<?php

namespace Martian\LaraCaptcha\Contracts;

interface DriverInterface
{
    /**
     * Display captcha challenge.
     * 
     * @param array $attributes
     * @return string
     */
    public function display(): string;

    /**
     * Display invisible captcha button.
     * 
     * @param string $formId Form identifier.
     * @param ?string $label Button label.
     * @param array $attributes
     * @return string
     * 
     * @link https://docs.hcaptcha.com/invisible
     */
    public function displayInvisibleButton(string $formId = null, ?string $label, array $attributes = []): string;

    /**
     * Display captcha javascript.
     * 
     * @param ?string $onload Callback function name.
     * @param ?bool $render Render parameter (explicit|onload)
     * @param ?string $locale Language code.
     * @param ?string $recaptchaCompat Compatibility mode (on|off).
     * @return string
     */
    public function script(?string $onload = null,bool $render = false, ?string $locale = null, ?string $recaptchaCompat = null): string;

   /**
     * Validate captcha challenge.
     * 
     * @param $res
     * @param string $ipAddress
     * @return bool
     */
    public function validate($res, string $ipAddress = null): bool;
}
