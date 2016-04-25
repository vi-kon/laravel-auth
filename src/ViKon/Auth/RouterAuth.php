<?php

namespace ViKon\Auth;

use Illuminate\Routing\Router;
use ViKon\Auth\Contracts\Keeper;

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

    /** @type \ViKon\Auth\Contracts\Keeper */
    protected $keeper;

    /**
     * @param \Illuminate\Routing\Router   $router
     * @param \ViKon\Auth\Contracts\Keeper $keeper
     */
    public function __construct(Router $router, Keeper $keeper)
    {
        $this->router = $router;
        $this->keeper = $keeper;
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
        $permissions = $this->getPermissions($name);
        $roles       = $this->getRoles($name);

        if ($permissions === null && $roles === null) {
            return null;
        }

        return $this->keeper->hasPermissions($permissions) && $this->keeper->hasRoles($roles);
    }

    /**
     * Check if authenticated user has access to current route
     *
     * Note: Route have to have name
     *
     * @return bool|null NULL if route not found, otherwise TRUE or FALSE depend if user has access to route or not
     */
    public function hasAccessToCurrentRoute()
    {
        return $this->hasAccess($this->router->current()->getName());
    }

    /**
     * Get permissions for named route
     *
     * @param string $name named route named
     *
     * @return string[]|null NULL if route not found, otherwise array of permissions
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

        if (array_key_exists('permission', $action)) {
            $permissions[] = $action['permission'];
        }

        if (array_key_exists('permissions', $action)) {
            $permissions = array_merge($permissions, $action['permissions']);
        }

        return array_unique($permissions);
    }

    /**
     * Get roles for named route
     *
     * @param string $name named route named
     *
     * @return string[]|null NULL if route not found, otherwise array of roles
     */
    public function getRoles($name)
    {
        $route = $this->router->getRoutes()->getByName($name);

        // If route not exists, return NULL
        if ($route === null) {
            return null;
        }

        $roles = [];

        // Get permissions from array syntax
        $action = $route->getAction();

        if (array_key_exists('role', $action)) {
            $roles[] = $action['role'];
        }

        if (array_key_exists('roles', $action)) {
            $roles = array_merge($roles, $action['roles']);
        }

        return array_unique($roles);
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