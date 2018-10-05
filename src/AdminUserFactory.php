<?php

namespace Moell\Mojito;


use Illuminate\Database\Eloquent\Model;

class AdminUserFactory
{
    /**
     * @return Model
     */
    public static function adminUser()
    {
        $key = 'auth.providers.' . config('mojito.super_admin.provider') . '.model';

        return app(config($key));
    }
}