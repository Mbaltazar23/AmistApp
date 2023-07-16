<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    //
    public function index()
    {
        $teacherId = Auth::id();
        $teacher = Teacher::where('user_id', $teacherId)->first();
        $data = [];
        $students = Student::where('course_id', $teacher->course_id)
            ->whereHas('course', function ($query) use ($teacher) {
                $query->where('college_id', $teacher->user->colleges->first()->college_id);
            })
            ->whereHas('user', function ($query) {
                $query->where('status', '<>', 0);
            })
            ->with('user')
            ->get();

        foreach ($students as $key => $student) {
            $data[] = [
                'id' => $student->id,
                'dni' => $student->user->dni,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'phone' => $student->user->phone,
                'points' => $student->user->points,
            ];
        }
        
        return response()->json($data);
    }
}
