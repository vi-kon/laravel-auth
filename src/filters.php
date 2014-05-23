<?php

Route::filter('auth.role', function ()
    {
        if (!Auth::check() && !Auth::viaRemember())
        {
            return Redirect::route(Config::get('auth::login.route'), array('redirect' => Request::path()));
        }
        $action = Route::getCurrentRoute()
                       ->getAction();

        if (isset($action['role']) &&
            is_string($action['role']) &&
            !AuthRole::hasRole($action['role'])
        )
        {
            return Redirect::route(Config::get('auth::error.403.route'));
        }
        if (isset($action['roles']) &&
            is_array($action['roles']) &&
            !AuthRole::hasRoles($action['roles'])
        )
        {
            return Redirect::route(Config::get('auth::error.403.route'));
        }

        return null;
    }
);

Route::filter('auth.home', function ()
    {
        if (Auth::check() && Auth::user()->home !== null)
        {
            return Redirect::route(Auth::user()->home);
        }

        return null;
    }
);
