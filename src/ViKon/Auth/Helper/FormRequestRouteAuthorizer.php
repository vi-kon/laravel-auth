<?php

namespace ViKon\Auth\Helper;

use Illuminate\Routing\Router;
use ViKon\Auth\RouterAuth;

/**
 * Class FormRequestRouteAuthorizer
 *
 * @package ViKon\Auth\Helper
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
trait FormRequestRouteAuthorizer
{
    /**
     * {@inheritDoc}
     */
    public function authorize(Router $router)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->make(RouterAuth::class)->hasAccess($router->current()->getName()) !== false;
    }
}