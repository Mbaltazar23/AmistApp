<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    //
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Cursos",
            'page_title' => 'Cursos',
            'page_functions_js' => 'functions_cursos.js',
        ];

        return view('courses.index', compact('data'));
    }

    public function getCourses()
    {
        $courses = Course::where('college_id', Auth::user()->colleges->first()->college_id)
            ->get();
        $data = [];
        foreach ($courses as $key => $course) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $btnAllDelete = '';
            $row = [
                'nro' => $key + 1,
                'nombre' => $course->name . ' ' . $course->section,
                'fecha' => $course->created_at->format('d-m-Y'),
                'hora' => $course->created_at->format('H:i:s'),
            ];
            if ($course->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $course->id . ')" title="Ver Curso"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $course->id . ')" title="Editar Curso"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $course->id . ')" title="Inhabilitar Curso"><i class="far fa-trash-alt"></i></button>';
                $btnAllDelete = '<button class="btn btn-secondary btn-sm" onClick="fntDelAll(' . $course->id . ')" title="Eliminar Curso" disabled><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $course->id . ')" title="Ver Curso" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $course->id . ')" title="Editar Curso" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $course->id . ')" title="Activar Curso"><i class="fas fa-toggle-on"></i></button>';
                $btnAllDelete = '<button class="btn btn-dark btn-sm" onClick="fntDelAll(' . $course->id . ')" title="Eliminar Curso"><i class="far fa-trash-alt"></i></button>';
            }
            $row['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnDelete . ' ' . $btnAllDelete . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setCourse(Request $request)
    {
        $id = $request->input('idCurso');
        $name = ucwords($request->input('txtNombre'));
        $section = $request->input('selectSection');
        $remember_token = Str::random(10);

        if ($id) {
            $course = Course::find($id);
            $course->name = $name;
            $course->section = $section;
            $course->save();
            return response()->json(['status' => true, 'msg' => 'Curso actualizado Exitosamente !!', 'data' => $course]);
        } else {
            $count = Course::where('name', $name)->count();

            if ($count > 0) {
                return response()->json(['status' => false, 'msg' => 'Este Curso ya existe']);
            } else {
                $course = new Course();
                $course->name = $name;
                $course->section = $section;
                $course->college_id = Auth::user()->colleges->first()->college_id;
                $course->remember_token = $remember_token;
                $course->save();
                return response()->json(['status' => true, 'msg' => 'Curso registrado Exitosamente !!', 'data' => $course]);
            }
        }
    }

    public function getCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $course->id,
                    'nombre' => $course->name,
                    'nombreCur' => $course->name . ' ' . $course->section,
                    'seccion' => $course->section,
                    'fecha' => $course->created_at->format('d-m-Y'),
                    'hora' => $course->created_at->format('H:i:s'),
                    'status' => $course->status,
                ],
                'msg' => 'Curso obtenida correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtener la categoría',
            ]);
        }
    }

    public function setStatus($id, Request $request)
    {
        $status = $request->input('status');
        $course = Course::find($id);

        if ($status == 0) {
            $studentsTeachers = Course::with(['students', 'teachers'])->find($id);

            if ($studentsTeachers) {
                return response()->json(['status' => false, 'msg' => 'Este Curso ya esta en uso']);
            }
        }

        $course->status = $status;
        $course->save();

        if ($status == 1) {
            $message = 'Curso Habilitado Exitosamente !!';
        } else {
            $message = 'Curso Inhabilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function deleteCourse($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['status' => false, 'msg' => 'El Curso no existe']);
        }
        $course->delete();

        return response()->json(['status' => true, 'msg' => "Curso Eliminado Exitosamente !!"]);
    }

    public function getReport()
    {
        $courses = Course::where('college_id', Auth::user()->colleges->first()->college_id)
            ->withCount(['students', 'teachers'])
            ->get();
        $data = [];
        foreach ($courses as $i => $course) {
            $data[] = [
                'nro' => $i + 1,
                'nombre' => $course->name . ' ' . $course->section,
                'fecha' => Carbon::parse($course->created_at)->format('d-m-Y'),
                'hora' => Carbon::parse($course->created_at)->format('H:i:s'),
                'students' => $course->students_count,
                'teachers' => $course->teachers_count,
                'status' => $course->status,
            ];
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    public function getSelectCourses($select)
    {
        $courses = Course::where('status', '!=', 0)
            ->where('college_id', Auth::user()->colleges->first()->college_id);

        if ($select == 'alumno') {
            $courses = $courses->whereHas('teachers');
        }

        $courses = $courses->with('teachers')->get();

        $html = '<option value="0">Seleccione un Curso</option>';
        foreach ($courses as $course) {
            $html .= '<option value="' . $course->id . '">' . $course->name . ' ' . $course->section . '</option>';
        }
        return $html;
    }

}
