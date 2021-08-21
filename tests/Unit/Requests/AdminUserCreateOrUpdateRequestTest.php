<?php

namespace Moell\Mojito\Tests\Unit\Requests;


use Moell\Mojito\Http\Requests\AdminUser\CreateOrUpdateRequest;
use Moell\Mojito\Tests\TestCase;
use Validator;


class AdminUserCreateOrUpdateRequestTest extends TestCase
{
    public function test_create_form_request()
    {
        $request = new CreateOrUpdateRequest();
        $request->setMethod('POST');

        $attributes = [
            'name' => 'test',
            'username' => 'test111',
            'password' => '12345544'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(false, $validator->fails());
    }

    public function test_update_form_request()
    {
        $request = new CreateOrUpdateRequest();
        $request->setMethod('PATCH');

        $attributes = [
            'name' => 'test',
            'password' => '123455'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(true, $validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }
}