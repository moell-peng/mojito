<?php

return [
    'admin_route_path' => env('ADMIN_ROUTE_PATH', 'admin'),

    'providers' => [
        'admin' => [
            'model' => \Moell\Mojito\Models\AdminUser::class,
            'login_fields' => [
                'email',
            ],
            'conditions' => [
                //['status', '=', 1]
            ]
        ]
    ]
];