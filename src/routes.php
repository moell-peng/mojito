<?php

$router = app('router');

$router->namespace('\Moell\Mojito\Http\Controllers')
    ->prefix(config("mojito.route_prefix", "api"))
    ->middleware(config("mojito.middleware.basic", "api"))
    ->group(function ($router) {
        $router->post("auth/login", "LoginController@authenticate");

        $router->middleware(config("mojito.middleware.auth", ['auth:sanctum']))->group(function ($router) {
            $router->post("auth/logout", "LoginController@logout")->name("auth.logout");
            $router->get('permission-user-all', 'PermissionController@allUserPermission')->name("permission.all-user-permission");
            $router->get('my-menu', 'MenuController@my')->name("menu.my");
            $router->patch('user-change-password', 'ChangePasswordController@changePassword')->name("user.change-password");
        });


        $router->middleware(config("mojito.middleware.permission", ['auth:sanctum', 'mojito.permission']))
            ->group(function ($router) {
                $router->apiResources([
                    'role' => 'RoleController',
                    'permission' => 'PermissionController',
                    'admin-user' => 'AdminUserController',
                    'permission-group' => 'PermissionGroupController',
                    'menu' => 'MenuController',
                ]);

                $router->get('role/{id}/permissions', 'RoleController@permissions')->name('role.permissions');
                $router->put('role/{id}/permissions', 'RoleController@assignPermissions')->name('role.assign-permissions');
                $router->get('guard-name-roles/{guardName}', 'RoleController@guardNameRoles')->name('role.guard-name-roles');
                $router->get('admin-user/{id}/roles/{guard}', 'AdminUserController@roles')->name('admin-user.roles');
                $router->put('admin-user/{id}/roles/{guard}', 'AdminUserController@assignRoles')->name('admin-user.assign-roles');
                $router->get('guard-name-for-permissions/{guardName}', 'PermissionGroupController@guardNameForPermissions')
                    ->name('permission-group.guard-name-for-permission');
                $router->get("permission-group-all", "PermissionGroupController@all")->name("permission-group.all");
            });
        });
