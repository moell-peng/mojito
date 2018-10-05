<?php

namespace Moell\Mojito\Tests\Unit\Requests;


use Moell\Mojito\Http\Requests\Menu\CreateOrUpdateRequest;
use Moell\Mojito\Tests\TestCase;
use Validator;

class MenuCreateOrUpdateRequestTest extends TestCase
{
    public function test_create_or_update_form_request()
    {
        $request = new CreateOrUpdateRequest();

        $attributes = [
            'parent_id' => 0,
            'name' => 'home',
            'icon' => 'fa fa-home',
            'uri' => '/',
            'is_link' => 1,
            'guard_name' => 'admin'
        ];

        $validator = Validator::make($attributes, $request->rules());

        $this->assertEquals(false, $validator->fails());

        $validator = Validator::make([], $request->rules());

        $errors = $validator->errors()->toArray();
        $keys = ['parent_id', 'name', 'guard_name', 'uri'];
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $errors);
        }
    }
}