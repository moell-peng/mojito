<?php

return [

    //Token effective days
    'passport_token_ttl' => env('PASSPORT_TOKEN_TTL', 1),

    //Refresh token valid days
    'passport_refresh_token_ttl' => env('PASSPORT_REFRESH_TOKEN_TTL', 7),

    'super_admin' => [
        'provider' => env('MOJITO_SUPER_ADMIN_PROVIDER', 'admin'),

        'auth' => env('MOJITO_SUPER_ADMIN_AUTH', 'auth:admin'),

        'guard' => env('MOJITO_SUPER_ADMIN_GRARD', 'admin'),
    ],

    'multi_auth_guards' => env('MOJITO_MULTI_AUTH_GUARDS'),

    'admin_route_path' => env('ADMIN_ROUTE_PATH', 'mojito'),

    'access_route' => ['permission.all-user-permission', 'menu.my'],

];
