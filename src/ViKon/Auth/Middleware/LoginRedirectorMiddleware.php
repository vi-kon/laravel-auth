<?php

namespace ViKon\Auth\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use ViKon\Auth\Contracts\Keeper;

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
        $router = $this->container->make('router');

        $currentRoute = $router->current();
        $action       = $currentRoute->getAction();

        if (isset($action['role'])) {
            $action['roles'] = $action['role'];
        }

        if (isset($action['permission'])) {
            $action['permissions'] = $action['permission'];
        }

        // Redirect guest to login screen if route has least one role or permission
        if ((isset($action['roles']) || isset($action['permissions'])) && !$this->keeper->check()) {
            $url      = $this->container->make('url');
            $log      = $this->container->make('log');
            $redirect = $this->container->make('redirect');

            $log->notice('Guest redirected to login screen', [
                'from' => $currentRoute->getName(),
                'to'   => $route,
            ]);

            return $redirect->guest($url->route($route));
        }

        return $next($request);
    }

}