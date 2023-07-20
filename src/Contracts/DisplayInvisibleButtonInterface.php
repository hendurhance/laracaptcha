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

namespace Martian\LaraCaptcha\Contracts;

interface DisplayInvisibleButtonInterface
{
    /**
     * Display invisible captcha button.
     * 
     * @param string $formId Form identifier.
     * @param ?string $label Button label.
     * @param array $attributes
     * @return string
     * 
     */
    public function displayInvisibleButton(string $formId = null, ?string $label, array $attributes = []): string;
}
