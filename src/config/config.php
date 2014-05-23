<?php

return array(
    'login'  => array(
        'route'    => 'login',
        'redirect' => 'home',
    ),
    'logout' => array(
        'route'    => 'logout',
        'redirect' => 'home',
    ),
    'error'  => array(
        '403' => array(
            'route' => 'error.403',
        ),
        '404' => array(
            'route' => 'error.404',
        ),
        '500' => array(
            'route' => 'error.500',
        ),
    ),
);
