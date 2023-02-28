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
}
