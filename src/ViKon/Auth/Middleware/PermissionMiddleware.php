<?php

namespace ViKon\Auth\Middleware;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        if (!$this->keeper->check() || !$this->keeper->hasPermission($permission)) {
            $router = $this->container->make('router');
            $log    = $this->container->make('log');

            $currentRoute = $router->current();

            // If user is not authenticated redirect to login route
            if (!$this->keeper->check()) {
                $config   = $this->container->make('config');
                $redirect = $this->container->make('redirect');
                $url      = $this->container->make('url');

                $log->notice('Guest redirected to login screen', [
                    'from' => $currentRoute->getName(),
                    'to'   => $config->get('vi-kon.auth.login.route'),
                ]);

                return $redirect->guest($url->route($config->get('vi-kon.auth.login.route')));
            }

            // If user is authenticated but has no permission to access given route then redirect to 403 route
            if (!$this->keeper->hasPermission($permission)) {
                $log->notice('User has no permission to view page', [
                    'user'       => $this->keeper->user()->toArray(),
                    'permission' => $permission,
                    'route'      => $currentRoute->getName(),
                ]);

                throw new AccessDeniedHttpException();
            }
        }

        return $next($request);
    }
}