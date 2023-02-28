<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Action;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PointAlumnAction;
use Illuminate\Support\Facades\Auth;

class PointAlumnActionController extends Controller
{
    //

    public function indexActionAlumns()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Compañeros del Alumno",
            'page_title' => 'Compañeros',
            'page_functions_js' => 'functions_companeros.js',
        ];

        return view('points.alumns', compact('data'));
    }

    public function indexActionsTeacher()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Alumnos del Profesor",
            'page_title' => 'Alumnos',
            'page_functions_js' => 'functions_alumnsC.js',
        ];

        return view('points.courseA', compact('data'));
    }

    public function getCompaniosAlum()
    {
        $userId = Auth::id();
        $user = User::with('colleges')->find($userId);
        $collegeId = $user->colleges->first()->college_id;

        $course = Course::where('college_id', $collegeId)
            ->whereHas('students', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        $students = $course->students()
            ->where('user_id', '<>', $userId)
            ->whereHas('user', function ($query) {
                $query->where('status', '<>', 0);
            })
            ->with('user')
            ->get();

        $data = [];

        foreach ($students as $key => $student) {
            $btnCanjProd = '';
            $row = [
                'dni' => $student->user->dni,
                'nombre' => $student->user->name,
                'email' => $student->user->email,
                'telefono' => $student->user->phone,
                'puntos' => $student->user->points,
            ];

            $btnCanjProd = '<button class="btn btn-dark btn-sm" onClick="fntCanjPoints(' . $student->user->id . ')" title="Dar puntos"><i class="fas fa-exchange-alt"></i>
            </button>';

            $row['options'] = '<div class="text-center">' . $btnCanjProd . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);

    }

    public function getAlumnsTeacher()
    {
        $teacherId = Auth::id();
        $teacher = Teacher::where('user_id', $teacherId)->first();

        $students = Student::where('course_id', $teacher->course_id)
            ->whereHas('course', function ($query) use ($teacher) {
                $query->where('college_id', $teacher->user->colleges->first()->college_id);
            })
            ->whereHas('user', function ($query) {
                $query->where('status', '<>', 0);
            })
            ->with('user')
            ->get();

        $data = [];

        foreach ($students as $key => $student) {
            $btnCanjProd = '';
            $row = [
                'dni' => $student->user->dni,
                'nombre' => $student->user->name,
                'email' => $student->user->email,
                'telefono' => $student->user->phone,
                'puntos' => $student->user->points,
            ];

            $btnCanjProd = '<button class="btn btn-dark btn-sm" onClick="fntCanjPoints(' . $student->user->id . ')" title="Dar puntos"><i class="fas fa-exchange-alt"></i>
                </button>';

            $row['options'] = '<div class="text-center">' . $btnCanjProd . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getStudentAlum($id)
    {
        $student = User::find($id);
        if ($student) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $student->id,
                    'idUserS' => Auth::user()->id,
                    'dni' => $student->dni,
                    'nombre' => $student->name,
                    'correo' => $student->email,
                    'direccion' => $student->address,
                    'status' => $student->status,
                ],
                'msg' => 'Estudiante obtenido correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtener la categoría',
            ]);
        }
    }

    public function setPointsDonate(Request $request)
    {
        $idSend = $request->input("idUserSen");
        $idRecep = $request->input("idUserRec");
        $idAction = $request->input("listActions");

        $action = Action::findOrFail($idAction);
        $points = $action->points;

        $pointAlumnAction = PointAlumnAction::where('user_send_id', $idSend)
            ->where('user_recept_id', $idRecep)
            ->first();

        if ($pointAlumnAction) {
            $pointAlumnAction->points += $points;
            $pointAlumnAction->save();
        } else {
            $pointAlumnAction = PointAlumnAction::create([
                'points' => $points,
                'user_send_id' => $idSend,
                'user_recept_id' => $idRecep,
                'action_id' => $idAction,
                'remember_token' => Str::random(10),
            ]);
        }

        $userRecep = User::findOrFail($idRecep);
        $userRecep->points += $points;
        $userRecep->save();

        $user = User::findOrFail($idSend);
        $user->points -= $action->points;
        $user->save();

        return response()->json(['status' => true, 'msg' => 'Puntos enviados Exitosamente !!']);
    }
}
