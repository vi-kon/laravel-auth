<?php

namespace ViKon\Auth\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use ViKon\Auth\Guard;

/**
 * Class HasAccessMiddleware
 *
 * @package ViKon\Auth\Middleware
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class HasAccessMiddleware
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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $action = $this->container->make('router')->current()->getAction();

        if (array_key_exists('permission', $action)) {
            $action['permissions'] = $action['permission'];
        }

        if (array_key_exists('permissions', $action)) {
            $url         = $this->container->make('url');
            $config      = $this->container->make('config');
            $redirect    = $this->container->make('redirect');
            $permissions = $action['permissions'];

            /** @noinspection ArrayCastingEquivalentInspection */
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }

            // If user is not authenticated redirect to login route
            if (!$this->guard->check()) {
                return $redirect->guest($url->route($config->get('vi-kon.auth.login.route')));
            }

            // If user is authenticated but has no permission to access given route then redirect to 403 route
            if (!$this->guard->hasPermissions($permissions)) {
                return $redirect->route($config->get('vi-kon.auth.error-403.route'))
                                ->with('route-request-uri', $request->getRequestUri())
                                ->with('route-permissions', $permissions);
            }
        }

        return $next($request);
    }
}