<?php

namespace Moell\Mojito\Tests;

use Laravel\Passport\PassportServiceProvider;
use Moell\Mojito\Models\AdminUser;
use Moell\Mojito\Providers\MojitoServiceProvider;
use Moell\Mojito\Tests\Fixtures\Http\Kernel;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SMartins\PassportMultiauth\Providers\MultiauthServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'mojito']);

        $this->loadMigrationsFrom(__DIR__ . '/Fixtures/database/migrations');

        $this->withFactories(__DIR__ . '/Fixtures/database/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'mojito');
        $app['config']->set('database.connections.mojito', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $this->setAuthConfigs();

        $this->setMojitoConfigs();

        $this->setPermissionConfigs();
    }

    protected function getPackageProviders($app)
    {
        return [
            PassportServiceProvider::class,
            MultiauthServiceProvider::class,
            PermissionServiceProvider::class,
            MojitoServiceProvider::class
        ];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    protected function setAuthConfigs()
    {
        config(['auth.guards.admin.driver' => 'passport']);
        config(['auth.guards.admin.provider' => 'admin']);
        config(['auth.providers.admin.driver' => 'eloquent']);
        config(['auth.providers.admin.model' => AdminUser::class]);
    }

    protected function setMojitoConfigs()
    {
        config(['mojito' => [
            'passport_token_ttl' => 1,
            'passport_refresh_token_ttl' => 7,
            'super_admin' => [
                'provider' => 'admin',
                'auth' => 'auth:admin',
                'guard' => 'admin'
            ]
        ]]);
    }

    protected function setPermissionConfigs()
    {
        config(['permission' => [
            'models' => [
                'permission' => Permission::class,
                'role' => Role::class,
            ],
            'table_names' => [
                'roles' => 'roles',
                'permissions' => 'permissions',
                'model_has_permissions' => 'model_has_permissions',
                'model_has_roles' => 'model_has_roles',
                'role_has_permissions' => 'role_has_permissions',
            ],
            'cache_expiration_time' => 60 * 24,
            'display_permission_in_exception' => false,
        ]]);
    }
}
