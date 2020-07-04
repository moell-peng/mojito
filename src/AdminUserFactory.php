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
        return app(config('mojito.providers.admin.model'));
    }
}