<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'points',
        'status',
        'created_at',
    ];

    public function pointsUserActions()
    {
        return $this->hasMany(PointAlumnAction::class);
    }

    public function collegeUsers()
    {
        return $this->hasManyThrough(CollegeUser::class, PointAlumnAction::class, 'action_id', 'id', 'id', 'user_recept_id');
    }

}
