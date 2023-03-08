<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'type',
        'points',
        'status',
        'expiration_date',
        'created_at',
    ];

    protected $dates = ['expiration_date'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function usersNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function getTimeLeftAttribute()
    {
        return $this->expiration_date->diffForHumans();
    }

    public function updateExpirationDate()
    {
        $this->expiration_date = Carbon::now()->addHours(72);
        $this->save();
    }

    public function updateStatusIfExpired()
    {
        if ($this->status == 0 && $this->expiration_date->isPast()) {
            $this->status = 1;
            $this->save();
        }
    }
}
