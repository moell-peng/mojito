<?php

namespace Moell\Mojito\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Authenticate
{
    /**
     * @author moell<moel91@foxmail.com>
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $permission = Route::currentRouteName();

        if (Auth::user()->hasPermissionTo($permission)) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions([$permission]);
    }
}