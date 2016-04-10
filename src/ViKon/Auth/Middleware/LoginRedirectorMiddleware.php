<?php

namespace ViKon\Auth\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class LoginRedirectorMiddleware
 *
 * @package ViKon\Auth\Middleware
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class LoginRedirectorMiddleware
{
    /** @var \Illuminate\Container\Container */
    protected $container;

    /** @type \Illuminate\Contracts\Auth\Guard */
    protected $guard;

    /**
     * @param \Illuminate\Container\Container  $container
     * @param \Illuminate\Contracts\Auth\Guard $guard
     */
    public function __construct(Container $container, Guard $guard)
    {
        $this->container = $container;
        $this->guard     = $guard;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $route route name
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, $route)
    {
        $action = $this->container->make('router')->current()->getAction();

        if (array_key_exists('role', $action)) {
            $action['roles'] = $action['role'];
        }

        if (array_key_exists('permission', $action)) {
            $action['permissions'] = $action['permission'];
        }

        if (array_key_exists('roles', $action) || array_key_exists('permissions', $action)) {
            $url      = $this->container->make('url');
            $redirect = $this->container->make('redirect');

            // If user is not authenticated redirect to login route
            if (!$this->guard->check()) {
                return $redirect->guest($url->route($route));
            }
        }

        return $next($request);
    }

}