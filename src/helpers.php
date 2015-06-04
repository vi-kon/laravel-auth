<?php

if (!function_exists('user_has_role')) {

    /**
     * Check if current user has single role
     *
     * @param string $role role name
     *
     * @return bool
     */
    function user_has_role($role)
    {
        return app('auth.role.user')->hasRole($role);
    }
}

if (!function_exists('user_has_roles')) {

    /**
     * Check if current user has all roles passed as parameter
     *
     * @param array|string $roles roles name array
     *
     * @return bool
     */
    function user_has_roles()
    {
        return app('auth.role.user')->hasRoles(func_get_args());
    }
}