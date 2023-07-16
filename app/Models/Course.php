<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'college_id',
        'status',
        'created_at',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(CourseTeacher::class,'course_id');
    }
}
