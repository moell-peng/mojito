<?php

namespace Moell\Mojito\Http\Controllers;


use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;
use Moell\Mojito\AdminUserFactory;
use Moell\Mojito\Http\Requests\AdminUser\CreateOrUpdateRequest;
use Moell\Mojito\Resources\AdminUser as AdminUserResource;
use Moell\Mojito\Resources\AdminUserCollection;
use Moell\Mojito\Resources\RoleCollection;

class AdminUserController extends Controller
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $adminUserModel;

    public function __construct()
    {
        $this->adminUserModel = AdminUserFactory::adminUser();
    }

    /**
     * @author moell<moel91@foxmail.com>
     * @param Request $request
     * @return AdminUserCollection
     */
    public function index(Request $request)
    {
        return new AdminUserCollection($this->adminUserModel->where(request_intersect(['name', 'username']))->paginate());
    }

    /**
     * @author moell<moel91@foxmail.com>
     * @param $id
     * @return AdminUserResource
     */
    public function show($id)
    {
        return new AdminUserResource($this->adminUserModel->findOrFail($id));
    }

    /**
     * @author moell<moel91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @return Response
     */
    public function store(CreateOrUpdateRequest $request)
    {
        $data = request_intersect([
            'name', 'username', 'password'
        ]);
        $data['status'] = $request->status ? true : false;
        $data['password'] = bcrypt($data['password']);

        $this->adminUserModel->create($data);

        return $this->created();
    }

    /**
     * @author moell<moel91@foxmail.com>
     * @param CreateOrUpdateRequest $request
     * @param $id
     * @return Response
     */
    public function update(CreateOrUpdateRequest $request, $id)
    {
        $adminUser = $this->adminUserModel->findOrFail($id);

        $data = request_intersect([
            'name', 'status'
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $adminUser->fill($data);
        $adminUser->save();

        return $this->noContent();
    }

    /**
     * @author moell<moel91@foxmail.com>
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $adminUser = $this->adminUserModel->findOrFail($id);

        $adminUser->delete();

        return $this->noContent();
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @param $provider
     * @return RoleCollection
     */
    public function roles($id, $provider)
    {
        $user = $this->getGuardModel($provider)->findOrFail($id);

        return new RoleCollection($user->roles);
    }

    /**
     * @param $id
     * @param $guard
     * @param Request $request
     * @return Response
     *@author moell<moell91@foxmail.com>
     */
    public function assignRoles($id, $guard, Request $request)
    {
        $user = $this->getGuardModel($guard)->findOrFail($id);

        $user->syncRoles($request->input('roles', []));

        return $this->noContent();
    }

    /**
     * @param $guard
     * @return Illuminate\Foundation\Auth\User
     */
    private function getGuardModel($guard)
    {
        return app(config('mojito.guards.' . $guard . '.model'));
    }
}