<?php

return [
    /*
    | --------------------------------------------------------------------------
    | Login settings
    | --------------------------------------------------------------------------
    | The login route for HasAccess middleware feature. If user is not
    | authenticated then HasAccess middleware redirect user to this route.
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
    | then HasAccess middleware redirect to this route.
    |
    */
    'error-403' => [
        'route' => 'error-403',
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
