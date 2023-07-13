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
  public function display(array $attributes = []): string;

  /**
   * Display captcha javascript.
   * 
   * @param ?string $onload Callback function name.
   * @param ?bool $render Render parameter (explicit|onload)
   * @param ?string $locale Language code.
   * @param ?string $recaptchaCompat Compatibility mode (on|off).
   * @return string
   */
  public function script(?string $onload = null, bool $render = false, ?string $locale = null, ?string $recaptchaCompat = null): string;

  /**
   * Validate captcha challenge.
   * 
   * @param $res
   * @param string $ipAddress
   * @return bool
   */
  public function validate($res, string $ipAddress = null): bool;
}
