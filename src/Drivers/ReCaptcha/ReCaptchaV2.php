<?php

namespace Martian\LaraCaptcha\Drivers\ReCaptcha;

use Martian\LaraCaptcha\Abstracts\ReCaptchaDriver;
use Martian\LaraCaptcha\Contracts\DisplayInvisibleButtonInterface;

class ReCaptchaV2 extends ReCaptchaDriver implements DisplayInvisibleButtonInterface
{
    /**
     * Display ReCaptcha challenge.
     * 
     * @param array $attributes
     * @return string
     */
    public function display(array $attributes = []): string
    {
        $attributes = $this->prepareAttributes($attributes);

        return '<div ' . $this->buildAttributes($attributes) . '></div>';
    }

    /**
     * Display invisible ReCaptcha button.
     * 
     * @param string $formId Form identifier.
     * @param string $label Button label.
     * @param array $attributes
     * @return string
     */
    public function displayInvisibleButton(string $formId = null, ?string $label, array $attributes = []): string
    {
        $script = '';

        if (!isset($attributes['data-callback'])) {
            $callbackFunction = 'onSubmit';
            $attributes['data-callback'] = $callbackFunction;
            $script = sprintf('<script>function %s(){document.getElementById("%s").submit();}</script>', $callbackFunction, $formId);
        }

        if (!isset($label) || empty($label)) {
            $label = 'Submit';
        }

        $attributes = $this->prepareAttributes($attributes);

        return '<button ' . $this->buildAttributes($attributes) . '>' . $label . '</button>' . "\n" . $script;
    }

    /**
     * Display ReCaptcha javascript.
     * 
     * @param ?string $onload Callback function name.
     * @param bool $render Render parameter (explicit|onload)
     * @param ?string $locale Language code.
     * @param ?string $recaptchaCompat Compatibility mode (on|off).
     * @return string
     */
    public function script(?string $onload = null, bool $render = false, ?string $locale = null, ?string $recaptchaCompat = null): string
    {
        // Check application locale.
        if (is_null($locale) && function_exists('app')) {
            $locale = app()->getLocale();
        }

        $params = http_build_query([
            'onload' => $onload,
            'render' => $render ? 'explicit' : 'onload',
            'hl' => $locale ?? $this->language,
        ]);

        return '<script src="' . $this->scriptUrl . '?' . $params . '" async defer></script>' . "\n";
    }

    /**
     * Validate ReCaptcha response.
     * 
     * @param $res
     * @param string $ipAddress
     * @return bool
     */
    public function validate($res, string $ipAddress = null): bool
    {
        if (empty($res)) {
            return false;
        }

        if (is_null($ipAddress)) {
            $ipAddress = request()->ip();
        }

        // Check if the response has already been verified
        if (in_array($res, $this->verifyResponses)) {
            return true;
        }

        try {
            $response = $this->client->post($this->verifyUrl, [
                'form_params' => [
                    'secret' => $this->secret,
                    'response' => $res,
                    'remoteip' => $ipAddress,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if (isset($body['success']) && $body['success'] === true) {
                $this->verifyResponses[] = $res;
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
