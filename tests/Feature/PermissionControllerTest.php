<?php

namespace Moell\Mojito\Tests\Feature;


use Spatie\Permission\Models\Permission;

class PermissionControllerTest extends FeatureTestCase
{
    public function test_get_permission_list()
    {
        $response = $this->get(route('permission.index') . '?name=admin-user.index', $this->jsonHeader());

        $data = [
            'name' => 'admin-user.index'
        ];

        $response
            ->assertStatus(200)
            ->assertJson($this->paginateResponseAssertFormat($data, 1));
    }

    public function test_create_a_permission()
    {
        $data = [
            'pg_id' => 1,
            'name' => 'permission.test',
            'guard_name' => 'admin',
            'display_name' => 'test',
            'icon' => 'fa fa-edit',
            'sequence' => '1',
            'description' => 'description'
        ];

        $response = $this->post(route('permission.store'), $data, $this->jsonHeader());

        $response->assertStatus(201);

        $permission = Permission::query()
            ->where('name', $data['name'])
            ->where('guard_name', $data['guard_name'])
            ->first();
        $this->assertNotNull($permission);
    }

    public function test_permission_id_for_details()
    {
        $permission = Permission::query()->first(['id', 'name', 'guard_name', 'display_name', 'icon', 'sequence', 'description']);

        $response = $this->get(route('permission.show', ['permission' => $permission->id]), $this->jsonHeader());
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => $permission->toArray()
            ]);

    }

    public function test_update_a_permission()
    {
        $data = [
            'pg_id' => 1,
            'name' => 'permission.test',
            'guard_name' => 'admin',
            'display_name' => 'test',
            'icon' => 'fa fa-edit',
            'sequence' => '1',
            'description' => 'description'
        ];

        $permission = Permission::query()->first();

        $response = $this->patch(
            route('permission.update', ['permission' => $permission->id]), $data, $this->jsonHeader()
        );

        $response->assertStatus(204);

        $this->assertArraySubset($data, Permission::query()->find($permission->id)->toArray());
    }

    public function test_delete_the_specified_ID_permission()
    {
        $permission = Permission::query()->first();

        $response = $this->delete(
            route('permission.destroy', ['permission' => $permission->id]), $this->jsonHeader()
        );

        $response->assertStatus(204);
        $this->assertNull(Permission::query()->find($permission->id));
    }

    public function test_user_all_permission()
    {
        $response = $this->get(route('permission.all-user-permission'), $this->jsonHeader());

        $response->assertStatus(200);
    }
}