<?php

namespace Martian\LaraCaptcha\Drivers\ReCaptcha;

use GuzzleHttp\Client;
use Martian\LaraCaptcha\Abstracts\Driver;
use Martian\LaraCaptcha\Exceptions\UnsupportedVersionException;

class ReCaptcha extends Driver
{
    /**
     * ReCaptcha secret key.
     * 
     * @var string
     */
    protected string $secret;

    /**
     * ReCaptcha site key.
     * 
     * @var string
     */
    protected string $siteKey;

    /**
     * ReCaptcha version.
     * 
     * @var string
     */
    protected string $version;

    /**
     * Site URL.
     * 
     * @var string
     */
    protected string $siteUrl;

    /**
     * ReCaptcha constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->scriptUrl = config('laracaptcha.drivers.recaptcha.script_url');
        $this->verifyUrl = config('laracaptcha.drivers.recaptcha.verify_url');
        $this->siteKey = config('laracaptcha.drivers.recaptcha.site_key');
        $this->secret = config('laracaptcha.drivers.recaptcha.secret_key');
        $this->version = config('laracaptcha.drivers.recaptcha.version');
        $this->siteUrl = config('laracaptcha.drivers.recaptcha.site_url');
        $this->client = new Client([
            'timeout' => config('laracaptcha.drivers.recaptcha.options.timeout'),
        ]);
    }

    /**
     * Display ReCaptcha challenge.
     * 
     * @param array $attributes
     * @return string
     */
    public function display(array $attributes = []): string
    {
        if ($this->version !== 'v2') {
            throw new UnsupportedVersionException('ReCaptcha challenge is only supported for ReCaptcha v2, try using displayV3() instead.');
        }

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
        if ($this->version !== 'v2') {
            throw new UnsupportedVersionException('Invisible ReCaptcha button is only supported for ReCaptcha v2.');
        }
        
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
        if ($this->version !== 'v2') {
            throw new UnsupportedVersionException('ReCaptcha script is only supported for ReCaptcha v2, try using scriptV3() instead.');
        }

        // Check application locale.
        if (!is_null($locale) && function_exists('app')) {
            $locale = app()->getLocale();
        }

        $params = http_build_query([
            'onload' => $onload,
            'render' => $render ? 'explicit' : 'onload',
            'hl' => $locale,
        ]);

        return '<script src="' . $this->scriptUrl . '?' . $params . '" async defer></script>' . "\n";
    }


    /**
     * Display ReCaptcha javascript for v3.
     * 
     * @return string
     */
    public function scriptV3(): string
    {
        if ($this->version !== 'v3') {
            throw new UnsupportedVersionException('ReCaptcha script is only supported for ReCaptcha v3, try using script() instead.');
        }

        return '<script src="' . $this->scriptUrl. '?render=' . $this->siteKey . '"></script>' . "\n";
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
        if ($this->version !== 'v2') {
            throw new UnsupportedVersionException('ReCaptcha validation is only supported for ReCaptcha v2, try using validateV3() instead.');
        }

        if(empty($res)) {
            return false;
        }

        if (is_null($ipAddress)) {
            $ipAddress = request()->ip();
        }

        // Check if the response has already been verified
        if(in_array($res, $this->verifyResponses)) {
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

    /**
     * Prepare attributes and apply defaults.
     * 
     * @param array $attributes
     * @return array
     */
    protected function prepareAttributes(array $attributes): array
    {
        $defaults = [
            'class' => 'g-recaptcha',
            'data-sitekey' => $this->siteKey,
        ];

        // Merge defaults with attributes, if other classes is set, append them.
        if (isset($attributes['class']) && !empty($attributes['class'])) {
            $defaults['class'] .= ' ' . $attributes['class'];
        }

        return array_merge($defaults, $attributes);
    }

    /**
     * Build HTML attributes from array.
     * 
     * @param array $attributes
     * @return string
     */
    protected function buildAttributes(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= $key . '="' . $value . '" ';
        }

        return trim($html);
    }

    /**
     * Display ReCaptcha challenge for v3.
     * 
     * @param array $attributes
     * @return string
     */
    public function displayV3(array $attributes = []): string
    {
        $attributes = $this->prepareAttributes($attributes);

        return '<div ' . $this->buildAttributes($attributes) . '></div>';
    }
}
