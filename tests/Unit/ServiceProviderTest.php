<?php

namespace Moell\Mojito\Tests\Unit;

use Laravel\Passport\PassportServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;
use Moell\Mojito\Providers\MojitoServiceProvider;
use Moell\Mojito\Tests\TestCase;
use SMartins\PassportMultiauth\Providers\MultiauthServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
    }

    public function test_if_the_service_provider_was_loaded()
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertArrayHasKey(PermissionServiceProvider::class, $providers);

        $this->assertArrayHasKey(MojitoServiceProvider::class, $providers);

        $this->assertArrayHasKey(SanctumServiceProvider::class, $providers);
    }
}
