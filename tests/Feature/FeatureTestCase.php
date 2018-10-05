<?php

namespace Moell\Mojito\Tests\Feature;


use Moell\Mojito\AdminUserFactory;
use Moell\Mojito\Database\MojitoTableSeeder;
use Moell\Mojito\Tests\TestCase;
use SMartins\PassportMultiauth\PassportMultiauth;

class FeatureTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => MojitoTableSeeder::class]);

        $this->passportAuth();
    }

    protected function passportAuth()
    {
        $user = AdminUserFactory::adminUser()->first();
        PassportMultiauth::actingAs($user);
    }

    /**
     * @param array $data
     * @param int $total
     * @param int $currentPage
     * @return array
     */
    protected function paginateResponseAssertFormat(array $data, $total = 0, $currentPage = 1)
    {
        return [
            'data' => [
                $data
            ],
            'links' => [
                "first" => true,
                "last" => true
            ],
            'meta' => [
                "current_page" => $currentPage > 1 ? $currentPage : true,
                "from"  => true,
                "last_page" => true,
                "path"  => true,
                "per_page" => true,
                "to" => true,
                "total" => $total > 0  ? $total : true
            ]
        ];
    }

    /**
     * @return array
     */
    protected function jsonHeader()
    {
        return [
            'Accept' => 'application/json'
        ];
    }
}