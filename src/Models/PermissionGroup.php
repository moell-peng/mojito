<?php

namespace Moell\Mojito\Models;


use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $guarded = ['id'];

    public function permission()
    {
        return $this->hasMany('Moell\Mojito\Models\Permission', 'pg_id');
    }
}