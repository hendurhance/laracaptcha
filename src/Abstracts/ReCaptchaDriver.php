<?php

namespace Martian\LaraCaptcha\Abstracts;

use GuzzleHttp\Client;

abstract class ReCaptchaDriver extends Driver
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
     * Whitelisted IP addresses.
     * 
     * @var array
     */
    protected array $whitelistedIpAddresses = [];

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
        $this->language = config('laracaptcha.language');
        $this->whitelistedIpAddresses = config('laracaptcha.skip_ips');
        $this->client = new Client([
            'timeout' => config('laracaptcha.drivers.recaptcha.options.timeout'),
        ]);
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
}
