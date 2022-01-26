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
    'route' => [
        // mojito 接口前缀
        'prefix' => env('MOJITO_ROUTE_PREFIX', 'api'),
        // 根路由中间件
        'root_middleware' => 'api',
        // 用户登录后不需要鉴权的基础接口的中间件（登出、改密等）
        'auth_middleware' => [
            'auth:sanctum',
        ],
        // 需要鉴权的管理接口的中间件（管理用户、角色、菜单、权限等）
        'manager_middleware' => [
            'auth:sanctum',
            'mojito.permission',
        ],
    ],
];
