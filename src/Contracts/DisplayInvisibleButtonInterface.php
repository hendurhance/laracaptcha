<?php

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
