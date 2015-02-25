<?php

if (!function_exists('user_has_role')) {

    function user_has_role($name) {
        return app('auth.role.user')->hasRole($name);
    }
}

if (!function_exists('user_has_roles')) {

    function user_has_role($name) {
        return app('auth.role.user')->hasRoles($name);
    }
}