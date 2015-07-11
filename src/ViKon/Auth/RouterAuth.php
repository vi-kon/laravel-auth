<?php

namespace ViKon\Auth;

use Illuminate\Routing\Router;

/**
 * Class RouterAuth
 *
 * @package ViKon\Auth
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class RouterAuth
{
    /** @type \Illuminate\Routing\Router */
    protected $router;

    /** @type \ViKon\Auth\Guard */
    protected $guard;

    /**
     * @param \Illuminate\Routing\Router $router
     * @param \ViKon\Auth\Guard          $guard
     */
    public function __construct(Router $router, Guard $guard)
    {
        $this->router = $router;
        $this->guard  = $guard;
    }

    /**
     * Check if current user has access to named route
     *
     * @param string $name named route name
     *
     * @return bool|null NULL if route not found, otherwise TRUE or FALSE depend if user has access to route or not
     */
    public function hasAccess($name)
    {
        if (($permissions = $this->getPermissions($name)) === null) {
            return null;
        }

        return $this->guard->hasPermissions(...$permissions);
    }

    /**
     * Get permissions for named route
     *
     * @param string $name named route named
     *
     * @return null NULL if route not found, otherwise array of permissions
     */
    public function getPermissions($name)
    {
        $route = $this->router->getRoutes()->getByName($name);

        // If route not exists, return NULL
        if ($route === null) {
            return null;
        }

        $permissions = [];

        // Get permission from permission middleware
        foreach ($route->middleware() as $middleware) {
            if (strpos($middleware, 'permission:') === 0) {
                list(, $permission) = explode(':', $middleware, 2);
                $permissions[] = $permission;
            }
        }

        // Get permissions from array syntax
        $action = $route->getAction();
        if (array_key_exists('permissions', $action)) {
            if (is_array($action['permissions'])) {
                $permissions = $action['permissions'];
            } else {
                $permissions[] = $action['permissions'];
            }
        }

        return array_unique($permissions);
    }

    /**
     * Check if route has is public (no permission restriction found)
     *
     * @param string $name named route name
     *
     * @return bool|null NULL if route not found, otherwise TRUE or FALSE depend if route has any permission or not
     */
    public function isPublic($name)
    {
        // If route not exists, return NULL
        if (($permissions = $this->getPermissions($name)) === null) {
            return null;
        }

        return count($permissions) === 0;
    }
}