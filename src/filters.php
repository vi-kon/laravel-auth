<?php

Route::filter('auth.role', function ()
    {
        if (!Auth::check() && !Auth::viaRemember())
        {
            return Redirect::guest(\URL::action(Config::get('auth::login.route')));
        }
        $action = Route::getCurrentRoute()
                       ->getAction();

        if (isset($action['roles']) && !AuthUser::hasRoles($action['roles']))
        {
            return Redirect::route(Config::get('auth::error.403.route'));
        }

        return null;
    }
);

Route::filter('auth.home', function ()
    {
        if (Auth::getUser() !== null && AuthUser::getUser()->home !== null)
        {
            return Redirect::route(AuthUser::getUser()->home);
        }

        return null;
    }
);
