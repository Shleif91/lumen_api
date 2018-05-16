<?php

declare(strict_types=1);

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    const ROLE_SUPER_ADMIN = 100;
    const ROLE_ADMIN = 30;
    const ROLE_MANAGER = 20;
    const ROLE_USER = 10;

    protected $fillable = [
        'name', 'email', 'last_login_at', 'role'
    ];

    protected $hidden = [
        'password',
    ];
}
