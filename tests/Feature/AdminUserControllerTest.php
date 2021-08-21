<?php

namespace Moell\Mojito\Tests\Feature;

use Moell\Mojito\Models\AdminUser;
use DB;

class AdminUserControllerTest extends FeatureTestCase
{
    public function test_paging_data_for_all_admin_users()
    {
        factory(AdminUser::class, 79)->create();

        $response = $this->get(route('admin-user.index'));

        $response->assertStatus(200);

        $data =  [
            'id' => true,
            'name' => true,
            'username' => true
        ];

        $response->assertJson($this->paginateResponseAssertFormat($data, AdminUser::count()));
    }

    public function test_admin_user_of_the_condition_to_be_filtered()
    {
        factory(AdminUser::class, 79)->create();

        $response = $this->get(route('admin-user.index') . '/?name=admin&username=admin');

        $response->assertStatus(200);

        $data =  [
            'name' => 'admin',
            'username' => 'admin'
        ];

        $response->assertJson($this->paginateResponseAssertFormat($data, 1));
    }

    public function test_store()
    {
        $data = [
            'name' => '111',
            'username' => 'admintest',
            'password' => '123446dd',
            'status' => 1,
        ];

        $response = $this->post(route('admin-user.store'), $data, $this->jsonHeader());
        $response->assertStatus(201);

        $adminUser = AdminUser::where('username', $data['username'])->first();
        $this->assertNotNull($adminUser);
    }

    public function test_show()
    {
        $adminUser = AdminUser::first();

        $response = $this->get(route('admin-user.show', ['admin_user' => $adminUser->id]));

        $response->assertJson([
            'data' => [
                'id' => 1,
                'name' => 'admin',
                'username' => 'admin'
            ]
        ]);
    }

    public function test_update()
    {
        $adminUser = AdminUser::first();

        $data = [
            'name' => 'admin_test'
        ];

        $response = $this->patch(route('admin-user.update', ['admin_user' => $adminUser->id]), $data, $this->jsonHeader());

        $response->assertStatus(204);

        $newAdminUser = AdminUser::first();
        $this->assertEquals($data['name'], $newAdminUser->name);
        $this->assertEquals($adminUser->password, $newAdminUser->password);
    }

    public function test_destroy()
    {
        $adminUser = AdminUser::first();

        $response = $this->delete(route('admin-user.destroy', ['admin_user' => $adminUser->id]), [], $this->jsonHeader());
        $response->assertStatus(204);

        $this->assertCount(0, $adminUser->roles);
        $this->assertCount(0, $adminUser->permissions);
    }

    public function test_get_a_list_of_roles_based_on_ID()
    {
        $adminUser = AdminUser::first();

        $route = route('admin-user.roles', ['id' => $adminUser->id, 'guard' => 'admin']);
        $response = $this->get($route, $this->jsonHeader());

        $data = [];
        foreach ($adminUser->roles as $role) {
            array_push($data, ['name' => $role->name]);
        }

        $response->assertStatus(200)
            ->assertJson([
                'data' => $data
            ]);
    }

    public function test_user_assign_roles()
    {
        $adminUser = AdminUser::first();

        $route = route('admin-user.roles', ['id' => $adminUser->id, 'guard' => 'admin']);
        $response = $this->put($route, ['roles' => []], $this->jsonHeader());

        $response->assertStatus(204);

        $this->assertCount(0, $adminUser->roles);
    }
}
