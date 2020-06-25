<?php

$router = app('router');

$router->namespace('\Moell\Mojito\Http\Controllers')
    ->prefix('api')
    ->middleware(['api', config('mojito.super_admin.auth'), 'mojito.permission'])
    ->group(function ($router) {
        $router->resource('role', 'RoleController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);

        $router->get('role/{id}/permissions', 'RoleController@permissions')->name('role.permissions');

        $router->put('role/{id}/permissions', 'RoleController@assignPermissions')->name('role.assign-permissions');

        $router->get('guard-name-roles/{guardName}', 'RoleController@guardNameRoles')->name('role.guard-name-roles');

        $router->resource('permission', 'PermissionController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);

        $router->resource('admin-user', 'AdminUserController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);

        $router->get('admin-user/{id}/roles/{provider}', 'AdminUserController@roles')->name('admin-user.roles');

        $router->put('admin-user/{id}/roles/{provider}', 'AdminUserController@assignRoles')->name('admin-user.assign-roles');

        $router->resource('permission-group', 'PermissionGroupController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);

        $router->get('guard-name-for-permissions/{guardName}', 'PermissionGroupController@guardNameForPermissions')
            ->name('permission-group.guard-name-for-permission');

        $router->get("permission-group-all", "PermissionGroupController@all")->name("permission-group.all");

        $router->resource('menu', 'MenuController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);

    });

$router->namespace('\Moell\Mojito\Http\Controllers')
    ->prefix('api')
    ->middleware(['api', config('mojito.super_admin.auth') . ',' . config('mojito.multi_auth_guards'), 'mojito.permission'])
    ->group(function ($router) {
        $router->get('permission-user-all', 'PermissionController@allUserPermission')->name('permission.all-user-permission');

        $router->get('my-menu', 'MenuController@my')->name('menu.my');

        $router->patch('user-change-password', 'ChangePasswordController@changePassword')->name('user.change-password');
    });

$router->view(config('mojito.admin_route_path') . "/{path}" , 'dashboard')->where("path", ".*")->middleware('web');

$router->namespace('\Moell\Mojito\Http\Controllers')
    ->prefix('api')
    ->middleware("api")
    ->group(function ($router) {
        $router->post("auth/login", "LoginController@authenticate");
        $router->get("auth/me", "LoginController@me")->middleware('auth:sanctum');
    });

$router->middleware(['api', config('mojito.super_admin.auth') . ',' . config('mojito.multi_auth_guards')])
    ->patch('api/user-change-password', '\Moell\Mojito\Http\Controllers\ChangePasswordController@changePassword')->name('user.change-password');
