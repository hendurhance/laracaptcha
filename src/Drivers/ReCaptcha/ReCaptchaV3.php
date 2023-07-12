<?php

namespace Martian\LaraCaptcha\Drivers\ReCaptcha;

use Martian\LaraCaptcha\Abstracts\ReCaptchaDriver;
use Symfony\Component\HttpFoundation\IpUtils;

class ReCaptchaV3 extends ReCaptchaDriver
{
    /**
     * Skip By IP
     * 
     * @var bool
     */
    protected bool $skipByIp = false;

    /**
     * reCaptcha Input Identifier
     * 
     * @var string
     */
    protected string $recaptchaInputIdentifier;

    /**
     * reCaptcha Input Name
     * 
     * @var string
     */
    protected string $recaptchaInputName;

    /**
     * reCaptchaV3 constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->skipByIp = $this->skipByIp();
        $this->recaptchaInputIdentifier = config('laracaptcha.recaptcha_input_identifier');
        $this->recaptchaInputName = config('laracaptcha.recaptcha_input_name');
    }

    /**
     * Display reCaptchaV3 challenge.
     * 
     * @param array $config
     * @return string
     */
    public function display(array $config = []): string
    {
        if($this->skipByIp) {
            return '';
        }
        
        $action = array_key_exists('action', $config) ? $config['action'] : 'homepage';
        $customValidation = array_key_exists('custom_validation', $config) ? $config['custom_validation'] : '';

        $recaptchaInputIdentifier = array_key_exists('recaptcha_input_identifier', $config) ? $config['recaptcha_input_identifier'] : $this->recaptchaInputIdentifier;

        if($customValidation) {
            $validateFunction = ($customValidation) ? "{$customValidation}(token);" : '';
        } else {
            $validateFunction = '';
        }

        $input = '<input type="hidden" name="' . $this->recaptchaInputName . '" id="' . $recaptchaInputIdentifier . '">' . "\n";
        $script = '
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute("' . $this->siteKey . '", {action: "' . $action . '"}).then(function(token) {
                    document.getElementById("' . $recaptchaInputIdentifier . '").value = token;
                    ' . $validateFunction . '
                });
            });
        </script>
        ';

        return $input . $script;
    }

    /**
     * Display reCaptcha javascript.
     * 
     * @return string
     */
    public function script(?string $onload = null, bool $render = false, ?string $locale = null, ?string $recaptchaCompat = null): string
    {
        return '<script src="' . $this->scriptUrl . '?render=' . $this->siteKey . '&hl=' . $this->language . '"></script>' . "\n";
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
       return false;
    }

    /**
     * Check if IP address is in the whitelist.
     * 
     * @return bool
     */
    protected function skipByIp(): bool
    {
        if (empty($this->whitelistedIpAddresses)) {
            return false;
        }

        return IpUtils::checkIp(request()->ip(), $this->whitelistedIpAddresses);
    }
}
