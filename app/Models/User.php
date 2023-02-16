<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = 'users';

    protected $fillable = [
        'id', 'dni', 'name', 'email', 'phone', 'password', 'created_at', 'address', 'status',
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function roles()
    {
        return $this->hasMany(UserRoles::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function colleges()
    {
        return $this->hasMany(CollegeUser::class);
    }

}
