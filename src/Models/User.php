<?php

namespace Moell\Mojito\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Moell\Mojito\Contacts\UserContact;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements UserContact
{
    protected $guard_name;

    use HasApiTokens, Notifiable, HasRoles;

    public function guardName()
    {
        return $this->guard_name;
    }

}