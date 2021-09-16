<?php

namespace Moell\Mojito\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Moell\Mojito\Http\Requests\Role\CreateOrUpdateRequest;
use Moell\Mojito\Models\Role;
use Moell\Mojito\Resources\PermissionCollection;
use Moell\Mojito\Resources\Role as RoleResource;
use Moell\Mojito\Resources\RoleCollection;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class RoleController extends Controller
{
    /**
     * @author moell<moell91@foxmail.com>
     * @param Request $request
     * @return RoleCollection
     */
    public function index(Request $request)
    {
        return new RoleCollection(Role::query()->where(request_intersect(['name']))->paginate());
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $guardName
     * @return RoleCollection
     */
    public function guardNameRoles($guardName)
    {
        return new RoleCollection(Role::query()->where('guard_name', $guardName)->get());
    }

    public function show($id)
    {
        return new RoleResource(Role::query()->findOrFail($id));
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        Role::create(request_intersect([
            'name', 'guard_name', 'description',
        ]));

        return $this->created();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        if (Role::where(request_intersect(['name', 'guard_name']))->where('id', '!=', $id)->count()) {
            throw RoleAlreadyExists::create($request->name, $request->guard_name);
        }

        $role = Role::query()->findOrFail($id);

        $role->update(request_intersect([
            'name', 'guard_name', 'description',
        ]));

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return PermissionCollection
     */
    public function permissions($id)
    {
        $role = Role::query()->findOrFail($id);

        return new PermissionCollection($role->permissions);
    }

    /**
     * Assign permission
     *
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions($id, Request $request)
    {
        $role = Role::query()->findOrFail($id);

        $role->syncPermissions($request->input('permissions', []));

        return $this->noContent();
    }

    /**
     * @author osindex<yaoiluo@gmail.com>
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function menus($id)
    {
        $role = Role::query()->findOrFail($id);
        return $this->success(['data' => $role->menus->pluck('id')]);
    }
    /**
     * Assign menu
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignMenus($id, Request $request)
    {
        $role = Role::query()->findOrFail($id);

        // 此接口未使用
        $role->menus()->sync($request->input('menus', []));

        return $this->noContent();
    }

    /**
     * Toggle menu
     *
     * @author osindex<yaoiluo@gmail.com>
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function toggleMenus($id, Request $request)
    {
        $role = Role::query()->findOrFail($id);

        $role->menus()->toggle($request->input('id', []));

        return $this->noContent();
    }
}
