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
        return new AdminUserCollection($this->adminUserModel->where(request_intersect(['name', 'email']))->paginate());
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
            'name', 'email', 'password'
        ]);
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
            'name'
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
        $user = $this->getProviderModel($provider)->findOrFail($id);

        return new RoleCollection($user->roles);
    }

    /**
     * @author moell<moell91@foxmail.com>
     * @param $id
     * @param $provider
     * @param Request $request
     * @return Response
     */
    public function assignRoles($id, $provider, Request $request)
    {
        $user = $this->getProviderModel($provider)->findOrFail($id);

        $user->syncRoles($request->input('roles', []));

        return $this->noContent();
    }

    /**
     * @param $provider
     * @return Illuminate\Foundation\Auth\User
     */
    private function getProviderModel($provider)
    {
        return app(config('mojito.providers.' . $provider . '.model'));
    }
}