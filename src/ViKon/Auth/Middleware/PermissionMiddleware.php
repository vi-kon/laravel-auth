<?php

namespace ViKon\Auth\Middleware;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use ViKon\Auth\Contracts\Keeper;

/**
 * Class PermissionMiddleware
 *
 * @package ViKon\Auth\Middleware
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class PermissionMiddleware
{
    /** @var \Illuminate\Container\Container */
    protected $container;

    /** @type \ViKon\Auth\Contracts\Keeper */
    protected $keeper;

    /**
     * @param \Illuminate\Container\Container $container
     * @param \ViKon\Auth\Contracts\Keeper    $keeper
     */
    public function __construct(Container $container, Keeper $keeper)
    {
        $this->container = $container;
        $this->keeper    = $keeper;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next, $permission)
    {
        $url      = $this->container->make('url');
        $config   = $this->container->make('config');
        $redirect = $this->container->make('redirect');

        // If user is not authenticated redirect to login route
        if (!$this->keeper->check()) {
            return $redirect->guest($url->route($config->get('vi-kon.auth.login.route')));
        }

        // If user is authenticated but has no permission to access given route then redirect to 403 route
        if (!$this->keeper->hasPermission($permission)) {
            return $redirect->route($config->get('vi-kon.auth.error-403.route'))
                            ->with('route-request-uri', $request->getRequestUri())
                            ->with('route-permissions', [$permission]);
        }

        return $next($request);
    }
}