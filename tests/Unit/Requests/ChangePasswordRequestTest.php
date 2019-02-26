<?php

namespace Moell\Mojito\Tests\Unit\Requests;


use Moell\Mojito\Http\Requests\ChangePasswordRequest;
use Moell\Mojito\Tests\TestCase;
use Validator;


class ChangePasswordRequestTest extends TestCase
{
    public function test_change_password_request()
    {
        $request = new ChangePasswordRequest();
        $request->setMethod('PATCH');

        $attributes = [
            'old_password' => 'testtest',
            'password' => 'test1esss',
            'password_confirmation' => 'test1esss'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(false, $validator->fails());
    }
}