<?php

$router = app('router');

$router->namespace('\Moell\Mojito\Http\Controllers')
	->prefix('api')
	->middleware(['api', config('mojito.super_admin.auth'), 'mojito.permission'])
	->group(function ($router) {
		$router->get('role/tree', 'RoleController@tree')->name('role.tree');

		$router->resource('role', 'RoleController', ['only' =>
			['index', 'show', 'store', 'update', 'destroy'],
		]);

		$router->get('role/{id}/permissions', 'RoleController@permissions')->name('role.permissions');

		$router->put('role/{id}/permissions', 'RoleController@assignPermissions')->name('role.assign-permissions');

		$router->get('guard-name-roles/{guardName}', 'RoleController@guardNameRoles')->name('role.guard-name-roles');

		$router->resource('permission', 'PermissionController', ['only' =>
			['index', 'show', 'store', 'update', 'destroy'],
		]);

		$router->resource('admin-user', 'AdminUserController', ['only' =>
			['index', 'show', 'store', 'update', 'destroy'],
		]);

		$router->get('admin-user/{id}/roles/{provider}', 'AdminUserController@roles')->name('admin-user.roles');

		$router->put('admin-user/{id}/roles/{provider}', 'AdminUserController@assignRoles')->name('admin-user.assign-roles');

		$router->resource('permission-group', 'PermissionGroupController', ['only' =>
			['index', 'show', 'store', 'update', 'destroy'],
		]);

		$router->get('guard-name-for-permissions/{guardName}', 'PermissionGroupController@guardNameForPermissions')
			->name('permission-group.guard-name-for-permission');

		$router->resource('menu', 'MenuController', ['only' =>
			['index', 'show', 'store', 'update', 'destroy'],
		]);

	});

$router->namespace('\Moell\Mojito\Http\Controllers')
	->prefix('api')
	->middleware(['api', config('mojito.super_admin.auth') . ',' . config('mojito.multi_auth_guards'), 'mojito.permission'])
	->group(function ($router) {
		$router->get('permission-user-all', 'PermissionController@allUserPermission')->name('permission.all-user-permission');

		$router->get('my-menu', 'MenuController@my')->name('menu.my');
	});

$router->namespace('\Moell\Mojito\Http\Controllers')->middleware('web')->group(function ($router) {
	$router->view('mojito', 'dashboard');
});
