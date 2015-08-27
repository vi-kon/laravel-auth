<?php

if (!function_exists('user_has_role')) {

    /**
     * Check if current user has single permission
     *
     * @param string $permission single permission
     *
     * @return bool
     */
    function user_has_permission($permission)
    {
        return app('auth.driver')->hasPermission($permission);
    }
}

if (!function_exists('user_has_roles')) {

    /**
     * Check if current user has all listed permissions
     *
     * @param string[] ...$permissions list of permissions
     *
     * @return bool
     */
    function user_has_permissions($permissions)
    {
        return app('auth.driver')->hasPermissions($permissions);
    }
}