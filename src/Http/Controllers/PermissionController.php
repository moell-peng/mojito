<?php

namespace Moell\Mojito\Http\Controllers;


use Illuminate\Http\Request;
use Moell\Mojito\Http\Requests\Permission\CreateOrUpdateRequest;
use Moell\Mojito\Resources\PermissionCollection;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Moell\Mojito\Models\Permission;
use Moell\Mojito\Resources\Permission as PermissionResource;
use Auth;

class PermissionController extends Controller
{
    /**
     * @author moell<moell91@foxmail.com>
     * @param Request $request
     * @return PermissionCollection
     */
    public function index(Request $request)
    {
        $permissions =tap(Permission::latest(), function ($query) {
            $query->where(request_intersect([
                'name', 'guard_name', 'pg_id'
            ]));
        })->with('group')->paginate();

        return new PermissionCollection($permissions);
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return PermissionResource
     */
    public function show($id)
    {
        return new PermissionResource(Permission::query()->findOrFail($id));
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        $attributes = $request->only([
            'pg_id', 'name', 'guard_name', 'display_name', 'icon', 'sequence', 'description'
        ]);
        $attributes['created_name'] = Auth::user()->name;

        Permission::create($attributes);

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
        $permission = Permission::query()->findOrFail($id);

        $attributes = $request->only([
            'pg_id', 'name', 'guard_name', 'display_name', 'icon', 'sequence', 'description'
        ]);

        $attributes['updated_name'] = Auth::user()->name;

        $isset = Permission::query()
            ->where(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']])
            ->where('id', '!=', $id)
            ->count();

        if ($isset) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        $permission->update($attributes);

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        permission::query()->findOrFail($id)->delete();

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public function allUserPermission()
    {
        return response()->json(['data' => Auth::user()->getAllPermissions()->pluck('name')]);
    }
}