<?php

return [
    'login'  => [
        'route'    => 'login',
        'redirect' => 'home',
    ],
    'logout' => [
        'route'    => 'logout',
        'redirect' => 'home',
    ],
    'error'  => [
        '403' => [
            'route' => 'error.403',
        ],
        '404' => [
            'route' => 'error.404',
        ],
        '500' => [
            'route' => 'error.500',
        ],
    ],
];
