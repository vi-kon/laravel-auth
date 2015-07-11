<?php

return [
    /*
    | --------------------------------------------------------------------------
    | Login settings
    | --------------------------------------------------------------------------
    | The login route for HasAccessMiddleware middleware feature. If user is not
    | authenticated then HasAccessMiddleware middleware redirect user to this route.
    | After successful authentication is redirected back.
    |
    */
    'login'     => [
        'route' => 'login',
    ],
    /*
    | --------------------------------------------------------------------------
    | Error 403
    | --------------------------------------------------------------------------
    | If user is authenticated and no sufficient permissions to access route,
    | then HasAccessMiddleware middleware redirect to this route.
    |
    */
    'error-403' => [
        'route' => 'error-403',
    ],
    /*
    | --------------------------------------------------------------------------
    | Database table names
    | --------------------------------------------------------------------------
    | This option allow overwrite default table names. Configured values are
    | used in models and migration files too.
    |
    | Caution: If migration files are already migration and values are changed
    | then migration down will fail.
    |
    */
    'table'     => [
        // User tables
        'users'                   => 'users',
        'user_roles'              => 'user_roles',
        'user_permissions'        => 'user_permissions',
        'user_password_reminders' => 'user_password_reminders',
        // Pivot tables
        'rel__user__role'         => 'rel__user__role',
        'rel__user__permission'   => 'rel__user__permission',
        'rel__role__permission'   => 'rel__role__permission',
    ],
    /*
    | --------------------------------------------------------------------------
    | Profile
    | --------------------------------------------------------------------------
    | This model class name provides class for user profile. In user profile you
    | can specify custom user fields. This model is connected to
    | ViKon\Auth\Model\User via one to one connection.
    |
    | Note: In profile model the user_id field need to be opposite side of
    | connection.
    |
    */
    'profile'   => 'App\Model\Profile',
];
