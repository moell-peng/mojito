<?php

namespace Moell\Mojito\Tests\Feature;


use Moell\Mojito\Models\PermissionGroup;

class permissionGroupControllerTest extends FeatureTestCase
{
    public function test_get_permission_group_list()
    {
        $response = $this->get(route('permission-group.index'), $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson($this->paginateResponseAssertFormat([
                'name' => true
            ]));
    }

    public function test_get_permission_group_all()
    {
        $response = $this->get(route('permission-group.all'), $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => true,
                        'name' => true
                    ]
                ]
            ]);
    }

    public function test_get_guard_name_group_permissions()
    {
        $response = $this->get(route('permission-group.guard-name-for-permission', ['guardName' => 'admin']), $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => true,
                        'name' => true,
                        'permission' => true
                    ]
                ]
            ]);
    }

    public function test_create_a_permission_group()
    {
        $data = ['name' => 'test permission group'];

        $response = $this->post(route('permission-group.store'), $data, $this->jsonHeader());

        $response->assertStatus(201);

        $this->assertNotNull(PermissionGroup::query()->where('name', $data['name'])->first());
    }

    public function test_permission_group_id_for_details()
    {
        $permissionGroup = PermissionGroup::query()->first();

        $response = $this->get(
            route('permission-group.show', ['permission_group' => $permissionGroup->id]), $this->jsonHeader()
        );

        $response
            ->assertStatus(200)
            ->assertJson(['data' => [
                'name' => $permissionGroup->name
            ]]);
    }

    public function test_update_a_permission_group()
    {
        $data = ['name' => 'test permission group'];

        $permissionGroup = PermissionGroup::query()->first();

        $response = $this->patch(
            route('permission-group.update', ['permission_group' => $permissionGroup->id]), $data, $this->jsonHeader()
        );

        $response->assertStatus(204);

        $new = PermissionGroup::query()->find($permissionGroup->id);

        $this->assertEquals($new->name, $data['name']);
    }

    public function test_delete_the_specified_ID_permission_group()
    {
        $permissionGroup = PermissionGroup::query()->first();

        $response = $this->delete(route('permission-group.destroy', ['permission_group' => $permissionGroup->id]));

        $response->assertStatus(422);

        $new = PermissionGroup::create([
            'name' => 'test'
        ]);

        $newResponse = $this->delete(route('permission-group.destroy', ['permission_group' => $new->id]));

        $newResponse->assertStatus(204);
        $this->assertNull(PermissionGroup::query()->find($new->id));
    }
}