<?php

namespace Martian\LaraCaptcha\Drivers\HCaptcha;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Martian\LaraCaptcha\Abstracts\Driver;
use Martian\LaraCaptcha\Contracts\DisplayInvisibleButtonInterface;

class HCaptcha extends Driver implements DisplayInvisibleButtonInterface
{
    /**
     * HCaptcha secret key.
     * 
     * @var string
     */
    protected string $secret;

    /**
     * HCaptcha site key.
     * 
     * @var string
     */
    protected string $siteKey;

    /**
     * HCaptcha constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->scriptUrl = config('laracaptcha.drivers.hcaptcha.script_url');
        $this->verifyUrl = config('laracaptcha.drivers.hcaptcha.verify_url');
        $this->siteKey = config('laracaptcha.drivers.hcaptcha.site_key');
        $this->secret = config('laracaptcha.drivers.hcaptcha.secret_key');
        $this->language = config('laracaptcha.language');
        $this->client = new Client([
            'timeout' => config('laracaptcha.drivers.hcaptcha.options.timeout'),
        ]);
    }

    /**
     * Display HCaptcha challenge.
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
     * Display invisible HCaptcha button.
     * 
     * @param string $formId Form identifier.
     * @param ?string $label Button label.
     * @param array $attributes
     * @return string
     */
    public function displayInvisibleButton(string $formId = null, ?string $label, array $attributes = []): string
    {
        $script = '';

        if (!isset($attributes['data-callback'])) {
            $callbackFunction = 'onSubmit';
            $attributes['data-callback'] = $callbackFunction;
            $script = sprintf('<script type="text/javascript">function %s(){document.getElementById("%s").submit();}</script>', $callbackFunction, $formId);
        }

        if (!isset($label) || empty($label)) {
            $label = 'Submit';
        }

        $attributes = $this->prepareAttributes($attributes);
        
        return '<button ' . $this->buildAttributes($attributes) . '>' . $label . '</button>'."\n".$script;
    }

    /**
     * Display HCaptcha javascript.
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
            'recaptchacompat' => $recaptchaCompat ? 'on' : 'off',
        ]);

        return '<script src="' . $this->scriptUrl . '?' . $params . '" async defer></script>'."\n";
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

        if (is_null($ipAddress)) {
            $ipAddress = request()->ip();
        }

        // Check if the response has already been verified.
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
            Log::debug('HCaptcha response: ' . json_encode($body));

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
    protected function prepareAttributes(array $attributes = []): array
    {
        $defaults = [
            'class' => 'h-captcha',
            'data-sitekey' => $this->siteKey,
        ];
        
        // Merge defaults with attributes, if class is set, append it.
        if (isset($attributes['class']) && !empty($attributes['class'])) {
            $defaults['class'] .= ' ' . $attributes['class'];
        }

        return array_merge($attributes, $defaults);
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
}
