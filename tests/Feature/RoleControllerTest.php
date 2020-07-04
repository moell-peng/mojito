<?php

namespace Moell\Mojito\Tests\Feature;


use Spatie\Permission\Models\Role;

class RoleControllerTest extends FeatureTestCase
{
    public function test_to_get_a_list_of_roles()
    {
        $response = $this->get(route('role.index') . '?name=admin', $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson($this->paginateResponseAssertFormat([
                'name' => 'admin'
            ]));
    }

    public function test_create_a_role()
    {
        $data = [
            'name' => 'test',
            'guard_name' => 'test',
            'description' => 'test description'
        ];

        $response = $this->post(route('role.store'), $data, $this->jsonHeader());

        $response->assertStatus(201);
    }

    public function test_edit_a_role()
    {
        $role = Role::query()->first();

        $data =  [
            'name' =>  'test',
            'guard_name' => 'test'
        ];

        $response = $this->patch(route('role.update', ['role' => $role->id]), $data, $this->jsonHeader());

        $response->assertStatus(204);

        $role = Role::query()->first();
        $this->assertEquals($data['name'], $role->name);
        $this->assertEquals($data['guard_name'], $role->guard_name);
    }

    public function test_role_assign_permission()
    {
        $role = Role::query()->first();

        $response = $this->put(route('role.assign-permissions', ['id' => $role->id]), ['permissions' => []], $this->jsonHeader());

        $response->assertStatus(204);

        $this->assertCount(0, $role->permissions);
    }

    public function test_guard_name_for_roels()
    {
        $response = $this->get(route('role.guard-name-roles', ['guardName' => 'admin']), $this->jsonHeader());

        $response->assertStatus(200)->assertJson([
            'data' => [
                ['guard_name' => 'admin']
            ]
        ]);
    }

    public function test_get_role_permissions()
    {
        $role = Role::query()->first();

        $response = $this->get(route('role.permissions', ['id' => $role->id]), $this->jsonHeader());

        $count = $role->permissions()->orderBy('id')->count();

        $response->assertStatus(200);

        $this->assertCount($count, json_decode($response->getContent())->data);
    }

    public function test_role_id_for_details()
    {
        $role = Role::query()->first();

        $response = $this->get(route('role.show', ['role' => $role->id]), $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $role->name,
                    'guard_name' => $role->guard_name
                ]
            ]);
    }

    public function test_role_id_for_destroy()
    {
        $role = Role::create(['name' => 'test', 'guard_name' => 'test']);

        $response = $this->delete(route('role.destroy', ['role' => $role->id]), $this->jsonHeader());

        $response->assertStatus(204);

        $this->assertNull(Role::query()->find($role->id));
    }

}
