<?php

namespace Martian\LaraCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Martian\LaraCaptcha\Skeleton\SkeletonClass
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
