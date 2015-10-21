<?php

namespace ViKon\Auth\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
            if (!$this->guard->check()) {
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
            if (!$this->guard->hasGroups($groups) || !$this->guard->hasRoles($roles) || !$this->guard->hasPermissions($permissions)) {
                $router = $this->container->make('router');

                // Check if config defined 403 error route exists or not
                if ($router->has($config->get('vi-kon.auth.error-403.route'))) {
                    return $redirect->route($config->get('vi-kon.auth.error-403.route'))
                                    ->with('route-request-uri', $request->getRequestUri())
                                    ->with('route-groups', $groups)
                                    ->with('route-roles', $roles)
                                    ->with('route-permissions', $permissions);
                }

                throw new HttpException(403);
            }
        }

        return $next($request);
    }
}