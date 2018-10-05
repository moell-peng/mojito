<?php

namespace Moell\Mojito\Tests\Unit\Requests;


use Moell\Mojito\Http\Requests\Permission\CreateOrUpdateRequest;
use Moell\Mojito\Tests\TestCase;
use Validator;

class PermissionCreateOrUpdateRequestTest extends TestCase
{
    public function test_create_or_update_form_request()
    {
        $request = new CreateOrUpdateRequest();

        $attributes = [
            'pg_id' => 1,
            'name' => 'permission.test',
            'guard_name' => 'admin',
            'display_name' => 'test',
            'icon' => 'fa fa-edit',
            'sequence' => '1',
            'description' => 'description'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(false, $validator->fails());


        $validator = Validator::make([], $request->rules());

        $this->assertEquals(true, $validator->fails());

        $keys = [
            'name', 'guard_name', 'display_name', 'pg_id'
        ];
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $validator->errors()->toArray());
        }
    }
}