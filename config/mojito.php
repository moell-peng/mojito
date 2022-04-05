<?php

return [
    'guards' => [
        'admin' => [
            'model' => \Moell\Mojito\Models\AdminUser::class,
            'login_fields' => [
                'username',
            ],
            'conditions' => [
                ['status', '=', 1]
            ]
        ]
    ],

    'route_prefix' => "api",

    'middleware' => [
        'basic' => 'api',

        'auth' => ['auth:sanctum'],

        'permission' => ['auth:sanctum', 'mojito.permission']
    ],

    'captcha_cache_ttl' => 2,
];