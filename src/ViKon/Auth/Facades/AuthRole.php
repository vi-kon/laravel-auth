<?php


namespace ViKon\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class AuthRole
 *
 * @package ViKon\Auth\Facades
 */
class AuthRole extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth-role';
    }
} 