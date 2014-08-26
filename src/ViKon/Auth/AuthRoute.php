<?php


namespace ViKon\Auth;

/**
 * Class AuthRoute
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
class AuthRoute
{
    /**
     * Get roles for a named route
     *
     * @param string $name route name
     *
     * @return array|null return null if route not found, otherwise array of roles
     */
    public function getRoles($name)
    {
        $route = \Route::getRoutes()
                       ->getByName($name);
        if ($route === null)
        {
            return null;
        }

        $roles  = array();
        $action = $route->getAction();

        if (array_key_exists('roles', $action))
        {
            if (is_array($action['roles']))
            {
                $roles = $action['roles'];
            }
            else
            {
                $roles[] = $action['roles'];
            }
        }

        return array_unique($roles);
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
        if ($roles === null)
        {
            return null;
        }

        return \AuthUser::hasRoles($this->getRoles($name));
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
        if ($roles === null)
        {
            return null;
        }

        return count($this->getRoles($name)) === 0;
    }
}