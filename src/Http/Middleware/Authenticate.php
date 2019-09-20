<?php

namespace Moell\Mojito\Http\Middleware;

use Auth;
use Route;
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
        
        if (in_array($permission, config('mojito.access_route')) || Auth::user()->can($permission)) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions([$permission]);
    }
}
