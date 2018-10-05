<?php

namespace Moell\Mojito\Tests\Unit\Requests;


use Moell\Mojito\Http\Requests\Role\CreateOrUpdateRequest;
use Moell\Mojito\Tests\TestCase;
use Validator;

class RoleCreateOrUpdateRequestTest extends TestCase
{
    public function test_create_or_update_form_request()
    {
        $request = new CreateOrUpdateRequest();

        $attributes = [
            'name' => 'name'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(true, $validator->fails());

        $failAttributes = [
            'name' => 'name',
            'guard_name' => 'guard_name'
        ];

        $validator = Validator::make($failAttributes, $request->rules());

        $this->assertEquals(false, $validator->fails());
    }
}