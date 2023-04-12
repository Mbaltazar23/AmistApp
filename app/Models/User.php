<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'id', 'dni', 'name', 'email', 'phone', 'points', 'password', 'created_at', 'address', 'status','remember_token'
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

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function pointsUserActions()
    {
        return $this->hasMany(PointAlumnAction::class, 'user_recept_id', 'id');
    }

    public function pointsUserActionsSent()
    {
        return $this->hasMany(PointAlumnAction::class, 'user_send_id', 'id');
    }
/**
 * Get the identifier that will be stored in the subject claim of the JWT.
 *
 * @return mixed
 */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
