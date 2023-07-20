<?php

/**
 * Copyright (c) Josiah Endurance
 * 
 * @package LaraCaptcha
 * @license MIT License
 * 
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @since 1.0.0
 * @see https://github.com/hendurhance/laracaptcha
 */

namespace Martian\LaraCaptcha\Abstracts;

use Martian\LaraCaptcha\Contracts\DriverInterface;

abstract class Driver implements DriverInterface
{
    /**
     * Javascript source url.
     * 
     * @var string
     */
    protected $scriptUrl;

    /**
     * Captcha verification url.
     * 
     * @var string
     */
    protected $verifyUrl;

    /**
     * Setup Client
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Already verified responses.
     * 
     * @var array
     */
    protected $verifyResponses = [];

    /**
     * Display captcha language.
     * 
     * @var string
     */
    protected $language;

    abstract public function __construct();
}
