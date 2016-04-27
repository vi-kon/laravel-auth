<?php

namespace ViKon\Auth\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use ViKon\Auth\Contracts\Keeper;

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
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $action = $this->container->make('router')->current()->getAction();

        if (array_key_exists('group', $action)) {
            $action['groups'] = $action['group'];
        }
        if (array_key_exists('role', $action)) {
            $action['roles'] = $action['role'];
        }
        if (array_key_exists('permission', $action)) {
            $action['permissions'] = $action['permission'];
        }

        // Check user access only if least one role or permission is added to current route
        if (array_key_exists('roles', $action) || array_key_exists('permissions', $action)) {
            $url      = $this->container->make('url');
            $config   = $this->container->make('config');
            $redirect = $this->container->make('redirect');

            // If user is not authenticated redirect to login route
            if (!$this->keeper->check()) {
                return $redirect->guest($url->route($config->get('vi-kon.auth.login.route')));
            }

            // Get roles and permissions
            $groups      = Arr::get($action, 'groups', []);
            $roles       = Arr::get($action, 'roles', []);
            $permissions = Arr::get($action, 'permissions', []);

            /** @noinspection ArrayCastingEquivalentInspection */
            if (!is_array($groups)) {
                $groups = [$groups];
            }
            /** @noinspection ArrayCastingEquivalentInspection */
            if (!is_array($roles)) {
                $roles = [$roles];
            }
            /** @noinspection ArrayCastingEquivalentInspection */
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }

            // If user is authenticated but has no permission to access given route then redirect to 403 route
            if ($this->keeper->hasGroups($groups) !== true ||
                $this->keeper->hasRoles($roles) !== true ||
                $this->keeper->hasPermissions($permissions) !== true
            ) {
                $router = $this->container->make('router');
                $log    = $this->container->make('log');

                $currentRoute = $router->current();

                $log->notice('User has no access to page', [
                    'user'        => $this->keeper->user()->toArray(),
                    'permissions' => $permissions,
                    'roles'       => $roles,
                    'groups'      => $groups,
                    'route'       => $currentRoute->getName(),
                ]);

                throw new AccessDeniedHttpException();
            }
        }

        return $next($request);
    }
}