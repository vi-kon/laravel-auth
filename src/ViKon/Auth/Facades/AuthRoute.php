<?php

namespace ViKon\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AuthRoute
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth\Facades
 */
class AuthRoute extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth.role.route';
    }
}