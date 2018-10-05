<?php

namespace Moell\Mojito\Tests\Unit\Requests;


use Moell\Mojito\Http\Requests\PermissionGroup\CreateOrUpdateRequest;
use Moell\Mojito\Tests\TestCase;
use Validator;

class PermissionGroupCreateOrUpdateRequestTest extends TestCase
{
    public function test_create_or_update_form_request()
    {
        $request = new CreateOrUpdateRequest();

        $attributes = [
            'name' => 'name'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(false, $validator->fails());

        $validator = Validator::make([], $request->rules());

        $this->assertEquals(true, $validator->fails());
    }
}