<?php

namespace Moell\Mojito\Http\Controllers;


use App\Member;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Moell\Mojito\Models\AdminUser;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $config = data_get(config("mojito.guards"), $request->guard);

        if (! $config) {
            return $this->forbidden("Undefined guard");
        }

        $conditions = data_get($config, 'conditions', []);

        $user = app($config['model'])->where(function ($query) use ($config) {
            foreach ($config['login_fields'] as $field) {
                $query->orWhere($field, request()->get('username'));
            }
            return $query;
        })->when($conditions, function ($query) use ($conditions) {
            return $query->where($conditions);
        })->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        PersonalAccessToken::query()->where("tokenable_type", $config['model'])
            ->where("name", $request->guard)
            ->where("tokenable_id", $user->id)
            ->delete();

        return response()->json([
            'data' => [
                'token' => $user->createToken($request->guard)->plainTextToken,
            ]
        ]);
    }

    /**
     * logout
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->noContent();
    }
}