<?php

namespace ViKon\Auth\Middleware;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use ViKon\Auth\Guard;

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

    /** @var \Illuminate\Auth\Guard */
    protected $guard;

    /**
     * @param \Illuminate\Container\Container $container
     * @param \ViKon\Auth\Guard               $guard
     */
    public function __construct(Container $container, Guard $guard)
    {
        $this->container = $container;
        $this->guard     = $guard;
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
        if (!$this->guard->check()) {
            return $redirect->guest($url->route($config->get('vi-kon.auth.login.route')));
        }

        // If user is authenticated but has no permission to access given route then redirect to 403 route
        if (!$this->guard->hasPermission($permission)) {
            return $redirect->route($config->get('vi-kon.auth.error-403.route'))
                            ->with('route-request-uri', $request->getRequestUri())
                            ->with('route-permissions', [$permission]);
        }

        return $next($request);
    }
}