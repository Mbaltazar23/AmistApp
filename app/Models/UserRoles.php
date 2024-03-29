<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    protected $fillable = [
        'id',
        'user_id',
        'role',
        'remember_token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
	
	public function hasRole($role)
    {
        return $this->role == $role;
    }
}
