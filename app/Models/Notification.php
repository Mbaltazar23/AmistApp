<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'type',
        'points',
        'status',
        'created_at',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function usersNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
