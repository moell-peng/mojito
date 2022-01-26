<?php

namespace Moell\Mojito\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Laravel\Passport\PassportServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;
use Moell\Mojito\Providers\MojitoServiceProvider;
use Moell\Mojito\Tests\Fixtures\Http\Kernel;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SMartins\PassportMultiauth\Providers\MultiauthServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use ArraySubsetAsserts;

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

        $this->setMojitoConfigs();

        $this->setPermissionConfigs();
    }

    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
            MojitoServiceProvider::class,
            SanctumServiceProvider::class
        ];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    protected function setMojitoConfigs()
    {
        config(['mojito' => include __DIR__ . '/../config/mojito.php']);
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
