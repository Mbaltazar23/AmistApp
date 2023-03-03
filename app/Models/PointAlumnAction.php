<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointAlumnAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'points',
        'user_send_id',
        'user_recept_id',
        'action_id',
        'remember_token',
    ];

    public function userSend()
    {
        return $this->belongsTo(User::class, 'user_send_id');
    }

    public function userRecept()
    {
        return $this->belongsTo(User::class, 'user_recept_id');
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
