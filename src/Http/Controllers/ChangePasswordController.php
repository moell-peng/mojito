<?php

namespace Moell\Mojito\Http\Controllers;


use Auth;
use Hash;
use Moell\Mojito\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    /**
     * @author moell
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if (! Hash::check($request->old_password, $user->password)) {
            return $this->unprocesableEtity([
                'password' => 'Incorrect password'
            ]);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return $this->noContent();
    }
}