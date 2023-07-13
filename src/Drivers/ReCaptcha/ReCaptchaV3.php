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
     * reCaptcha Score Enabled
     * 
     * @var string
     */
    protected string $recaptchaScoreEnabled;

    /**
     * reCaptcha Settings
     * 
     * @var string
     */
    protected array $recaptchaSettings;

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
        $this->recaptchaScoreEnabled = config('laracaptcha.recaptcha_score_enabled');
        $this->recaptchaSettings = config('laracaptcha.recaptcha_v3_settings');
    }

    /**
     * Display reCaptchaV3 challenge.
     * 
     * @param array $config
     * @return string
     */
    public function display(array $config = []): string
    {
        if ($this->skipByIp) {
            return '';
        }

        $action = array_key_exists('action', $config) ? $config['action'] : $this->recaptchaSettings['action'];
        $customValidation = array_key_exists('custom_validation', $config) ? $config['custom_validation'] : '';

        $recaptchaInputIdentifier = array_key_exists('recaptcha_input_identifier', $config) ? $config['recaptcha_input_identifier'] : $this->recaptchaInputIdentifier;

        if ($customValidation) {
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
        if (function_exists('app')) {
            $this->language = app()->getLocale() ?? config('laracaptcha.locale');
        }
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
        if (!$this->recaptchaScoreEnabled || ($ipAddress && $this->skipByIp())) {
            return true;
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
                if ($this->recaptchaScoreEnabled) {
                    if($this->recaptchaSettings['score_comparison'] && !$this->validScore($body['score'])) {
                        return false;
                    }

                    if (!$this->validHostname($body['hostname'])) {
                        return false;
                    }

                    if (!$this->validAction($body['action'])) {
                        return false;
                    }
                }

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
     * Check if reCaptcha Action is valid.
     * 
     * @param string $action
     * @return bool
     */
    public function validAction(string $action): bool
    {
        if ($action && $action === $this->recaptchaSettings['action']) {
            return true;
        }

        return false;
    }

    /**
     * Check if reCaptcha Score is valid.
     * 
     * @param float $score
     * @return bool
     */
    public function validScore(float $score): bool
    {
        if ($score && $score >= $this->recaptchaSettings['minimum_score']) {
            return true;
        }

        return false;
    }

    /**
     * Check if reCaptcha Hostname is valid.
     * 
     * @param string $hostname
     * @return bool
     */
    public function validHostname(string $hostname): bool
    {
        if ($hostname && $hostname === $this->siteUrl) {
            return true;
        }

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
