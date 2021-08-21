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
    ]
];