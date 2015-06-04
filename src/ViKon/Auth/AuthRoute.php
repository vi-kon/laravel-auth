<?php

namespace ViKon\Auth;

use Illuminate\Routing\Router;

/**
 * Class AuthRoute
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
class AuthRoute
{
    /** @var \Illuminate\Routing\Router */
    protected $router;

    /** @var \ViKon\Auth\AuthUser */
    protected $authUser;

    /**
     * Create new AuthRoute instance
     *
     * @param \Illuminate\Routing\Router $router
     * @param \ViKon\Auth\AuthUser       $authUser
     */
    public function __construct(Router $router, AuthUser $authUser)
    {
        $this->router   = $router;
        $this->authUser = $authUser;
    }

    /**
     * Check if current user has access to named route
     *
     * @param string $name route name
     *
     * @return bool|null return null if route not found, otherwise true or false
     */
    public function hasCurrentUserAccess($name)
    {
        $roles = $this->getRoles($name);
        if ($roles === null) {
            return null;
        }

        return $this->authUser->hasRoles($this->getRoles($name));
    }

    /**
     * Get roles for a named route
     *
     * @param string $name route name
     *
     * @return array|null return null if route not found, otherwise array of roles
     */
    public function getRoles($name)
    {
        $route = $this->router->getRoutes()->getByName($name);
        if ($route === null) {
            return null;
        }

        $roles  = [];
        $action = $route->getAction();

        if (array_key_exists('roles', $action)) {
            if (is_array($action['roles'])) {
                $roles = $action['roles'];
            } else {
                $roles[] = $action['roles'];
            }
        }

        return array_unique($roles);
    }

    /**
     * Check if route has no roles
     *
     * @param string $name route name
     *
     * @return bool|null return null if route not found, otherwise true or false
     */
    public function isPublic($name)
    {
        $roles = $this->getRoles($name);
        if ($roles === null) {
            return null;
        }

        return count($this->getRoles($name)) === 0;
    }
}