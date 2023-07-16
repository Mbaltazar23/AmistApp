<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = "teachers";

    protected $fillable = [
        'user_id',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses() 
    {
        return $this->belongsToMany(Course::class, 'course_teachers', 'teacher_id', 'course_id');
    }
}
