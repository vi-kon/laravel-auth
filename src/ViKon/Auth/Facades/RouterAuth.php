<?php

namespace ViKon\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class RouterAuth
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth\Facades
 */
class RouterAuth extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vi-kon.auth.router';
    }
}