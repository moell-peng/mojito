<?php

namespace Moell\Mojito\Tests\Feature;


use Moell\Mojito\Models\Menu;

class MenuControllerTest extends FeatureTestCase
{
    public function test_to_get_a_list_of_menus()
    {
        $response = $this->get(route('menu.index'), $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => 1,
                        'parent_id' => '0',
                        'name' => 'Dashboard',
                        'guard_name' => "admin"
                    ],
                    [
                        'id' => 2,
                        'parent_id' => '0',
                        'name' => '系统管理',
                        'guard_name' => "admin",
                        'children' => [
                            [
                                'id' => 3,
                                'parent_id' => '2',
                                'name' => '管理员',
                                'guard_name' => "admin"
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_menu_after_user_login()
    {
        $response = $this->get(route('menu.my'), $this->jsonHeader());

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => 1,
                        'parent_id' => '0',
                        'name' => 'Dashboard',
                        'guard_name' => "admin"
                    ],
                    [
                        'id' => 2,
                        'parent_id' => '0',
                        'name' => '系统管理',
                        'guard_name' => "admin",
                        'children' => [
                            [
                                'id' => 3,
                                'parent_id' => '2',
                                'name' => '管理员',
                                'guard_name' => "admin"
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_create_a_menu()
    {
        $data = [
            'parent_id' => 0,
            'name' => 'home',
            'icon' => 'fa fa-home',
            'uri' => '/',
            'is_link' => 1,
            'guard_name' => 'admin',
            'sequence' => 10
        ];

        $response = $this->post(route('menu.store'), $data, $this->jsonHeader());

        $response->assertStatus(201);

        $menu = Menu::where('name', $data['name'])->where('guard_name', $data['guard_name'])->first()->toArray();
        $this->assertArraySubset($data, $menu);
    }

    public function test_edit_a_menu()
    {
        $data = [
            'parent_id' => 0,
            'name' => 'home',
            'icon' => 'fa fa-home',
            'uri' => '/',
            'is_link' => 1,
            'guard_name' => 'admin',
            'sequence' => 0
        ];

        $menu = Menu::query()->where('parent_id', '>', 0)->first();

        $response = $this->patch(route('menu.update', ['menu' => $menu->id]), $data, $this->jsonHeader());
        $response->assertStatus(204);

        $this->assertArraySubset($data, Menu::query()->find($menu->id)->toArray());
    }

    public function test_menu_id_for_details()
    {
        $menu = Menu::query()->first();

        $response = $this->get(route('menu.show', ['menu' => $menu->id]), $this->jsonHeader());
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $menu->name,
                    'guard_name' => $menu->guard_name
                ]
            ]);
    }

    public function test_menu_id_for_destroy()
    {
        $menu = Menu::query()->first();

        $childMenu = Menu::create([
            'name' => 'test',
            'guard_name' => 'test',
            'parent_id' => $menu->id
        ]);

        $response = $this->delete(route('menu.destroy', ['menu' => $menu->id]), $this->jsonHeader());
        $response->assertStatus(422);

        $childResponse = $this->delete(route('menu.destroy', ['menu' => $childMenu->id]), $this->jsonHeader());
        $childResponse->assertStatus(204);
    }

}
