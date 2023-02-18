<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text_question',
        'notification_id',
        'created_at',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
