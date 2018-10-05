<?php

namespace Moell\Mojito\Tests\Unit;

use Laravel\Passport\PassportServiceProvider;
use Moell\Mojito\Providers\MojitoServiceProvider;
use Moell\Mojito\Tests\TestCase;
use SMartins\PassportMultiauth\Providers\MultiauthServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function setUp()
    {
        $this->setUpTheTestEnvironment();
    }

    public function test_if_the_service_provider_was_loaded()
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertArrayHasKey(PassportServiceProvider::class, $providers);

        $this->assertArrayHasKey(MultiauthServiceProvider::class, $providers);

        $this->assertArrayHasKey(PermissionServiceProvider::class, $providers);

        $this->assertArrayHasKey(MojitoServiceProvider::class, $providers);
    }
}
