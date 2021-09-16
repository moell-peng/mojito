<?php

namespace Moell\Mojito\Models;

class Role extends \Spatie\Permission\Models\Role
{
    public function menus()
    {
        return $this->belongsToMany(
            Menu::class,
            'role_has_menus',
            'role_id',
            'menu_id'
        );
    }
}
