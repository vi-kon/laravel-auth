<?php

return [
    /*
    | --------------------------------------------------------------------------
    | Login settings
    | --------------------------------------------------------------------------
    | If user is not authenticated then authentication middleware redirect user
    | to this route. After successful authentication is redirected back to
    | original route.
    |
    */
    'login'   => [
        'route' => 'login',
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
    'table'   => [
        // User tables
        'users'                   => 'users',
        'user_roles'              => 'user_roles',
        'user_permissions'        => 'user_permissions',
        'user_password_reminders' => 'user_password_reminders',
        'user_groups'             => 'user_groups',
        // Pivot tables
        'rel__user__role'         => 'rel__user__role',
        'rel__user__permission'   => 'rel__user__permission',
        'rel__role__permission'   => 'rel__role__permission',
        'rel__user__group'        => 'rel__user__group',
        'rel__group__role'        => 'rel__group__role',
        'rel__group__permission'  => 'rel__group__permission',
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
    'profile' => 'App\Model\Profile',
];
