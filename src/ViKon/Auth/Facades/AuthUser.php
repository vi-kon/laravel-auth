<?php

namespace ViKon\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AuthUser
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth\Facades
 */
class AuthUser extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth.role.user';
    }
}