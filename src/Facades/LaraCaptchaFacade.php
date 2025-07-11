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
namespace Martian\LaraCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Martian\LaraCaptcha\LaraCaptcha
 */
class LaraCaptchaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laracaptcha';
    }
}
