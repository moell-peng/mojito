<?php

namespace Moell\Mojito\Tests\Unit;


use Moell\Mojito\AdminUserFactory;
use Moell\Mojito\Models\AdminUser;
use Moell\Mojito\Tests\TestCase;

class AdminUserFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
    }

    public function test_if_it_is_admin_user()
    {
        $model = AdminUserFactory::adminUser();

        $this->assertInstanceOf(AdminUser::class, $model);
    }
}