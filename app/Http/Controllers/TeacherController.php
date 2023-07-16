<?php

namespace App\Http\Controllers;

use App\Models\CollegeUser;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    //
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Profesores",
            'page_title' => 'Profesores',
            'page_functions_js' => 'functions_profesors.js',
        ];

        return view('teachers.index', compact('data'));
    }

    public function getTeachers()
    {
        $college_id = Auth::user()->colleges->first()->college_id;

        $teachers = User::whereHas('roles', function ($query) {
            $query->where('role', 'Profesor');
        })->whereHas('teachers.courses', function ($query) use ($college_id) {
            $query->where('college_id', $college_id);
        })->with('teachers.courses')->get();

        $data = [];
        foreach ($teachers as $key => $user) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $btnAllDelete = '';
            foreach ($user->teachers as $teacher) {

                $courses = $teacher->courses->map(function ($course) {
                    return $course->name . ' ' . $course->section;
                })->implode(', ');

                $row = [
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'cursos' => $courses,
                ];
                if ($user->status == 1) {
                    $row['status'] = '<span class="badge badge-success">Activo</span>';
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Profesor"><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Profesor"><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $user->id . ')" title="Inhabilitar Profesor"><i class="far fa-trash-alt"></i></button>';
                    $btnAllDelete = '<button class="btn btn-secondary btn-sm" onClick="fntDelAll(' . $user->id . ')" title="Eliminar Profesor" disabled><i class="far fa-trash-alt"></i></button>';
                } else {
                    $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                    $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Profesor" disabled><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Profesor" disabled><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $user->id . ')" title="Activar Profesor"><i class="fas fa-toggle-on"></i></button>';
                    $btnAllDelete = '<button class="btn btn-dark btn-sm" onClick="fntDelAll(' . $user->id . ')" title="Eliminar Profesor"><i class="far fa-trash-alt"></i></button>';
                }
                $row['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete.' '.$btnAllDelete. '</div>';
                $data[] = $row;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);

    }

    public function setTeacher(Request $request)
    {
        $id = $request->input('idProfe');
        $dni = $request->input('txtRutT');
        $name = ucwords($request->input('txtNombres'));
        $email = ucfirst($request->input('txtCorreoT'));
        $courses = $request->input('listCursos'); // Asegúrate que coincida con el atributo name del select múltiple en el formulario
        $phone = $request->input('txtTelefono');
        $address = $request->input('txtDireccion');
        $password = bcrypt($request->input("txtPassword")) ?? bcrypt('AmistApp.');
        $points = $request->input("txtPuntajeInicial") ?? 500;
        $college_id = Auth::user()->colleges->first()->college_id;
        $rol = env("ROLPROFE");

        if ($id) {
            // Actualizamos los datos del profesor
            $user = User::find($id);
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->password = $password;
            $user->address = $address ? $address : '';
            $user->points = $points;
            $user->save();

            // Actualizar los cursos del profesor
            $teacher = Teacher::where('user_id', $id)->first();
            $teacher->courses()->sync($courses);

            return response()->json(['status' => true, 'msg' => 'Profesor actualizado Exitosamente !!']);
        } else {
            // Insertar un nuevo registro del profesor
            $user = new User();
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->address = $address ? $address : '';
            $user->password = $password;
            $user->points = $points;
            $user->remember_token = Str::random(10);
            $user->save();

            // Crear un nuevo registro de Teacher
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->save();

            // Asociar el registro de User con el registro de CollegeUser correspondiente
            $college_user = new CollegeUser();
            $college_user->college_id = $college_id;
            $college_user->user_id = $user->id;
            $college_user->remember_token = Str::random(10);
            $college_user->save();

            // Asignar el rol de "profesor" al usuario
            $user_role = new UserRoles();
            $user_role->user_id = $user->id;
            $user_role->role = $rol;
            $user_role->remember_token = Str::random(10);
            $user_role->save();

            // Asociar los cursos al profesor
            $teacher->courses()->sync($courses);

            return response()->json(['status' => true, 'msg' => 'Profesor registrado Exitosamente !!']);
        }
    }

    public function getTeacher($id)
    {
        $teacher = User::where('id', $id)
            ->whereHas('roles', function ($query) {
                $query->where('role', env("ROLPROFE"));
            })
            ->with('teachers.courses')
            ->first();

        if ($teacher) {
            $courses = $teacher->teachers->pluck('courses')->flatten()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name . ' ' . $course->section,
                ];
            })->toArray();

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $teacher->id,
                    'dni' => $teacher->dni,
                    'nombre' => $teacher->name,
                    'email' => $teacher->email,
                    'direccion' => $teacher->address,
                    'telefono' => $teacher->phone,
                    'fecha' => $teacher->created_at->format('d-m-Y'),
                    'hora' => $teacher->created_at->format('H:i:s'),
                    'status' => $teacher->status,
                    'cursos' => $courses,
                ],
                'msg' => 'Profesor obtenido correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtener al profesor',
            ]);
        }
    }

    public function setStatus($id, Request $request)
    {
        $status = $request->input('status');

        $teacher = User::find($id);

        if (!$teacher) {
            return response()->json(['status' => false, 'msg' => 'El Profesor no existe']);
        }

        if ($status == 0) {
            // Si el status es 0, comprobar si el profesor tiene un curso asociado con alumnos
            $course = $teacher->teachers->first()->course;
            if ($course && $course->students()->whereHas('user', function ($query) {
                $query->where('status', 1);
            })->count() > 0) {
                return response()->json(['status' => false, 'msg' => 'Este Profesor tiene un curso asociado con alumnos activos', 'data' => $teacher]);
            } else {
                $teacher->status = $status;
                $teacher->save();
                $message = 'Profesor Inhabilitado Exitosamente !!';
            }
        } else {
            $teacher->status = $status;
            $teacher->save();
            $message = 'Profesor Habilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function deleteTeacher($id){
        $teacher = User::find($id);

        if (!$teacher) {
            return response()->json(['status' => false, 'msg' => 'El Profesor no existe']);
        }

        $teacher->delete();

        return response()->json(['status' => true, 'msg' => 'Profesor Eliminado Exitosamente !!']);
    }

    public function getReport()
    {
        $college_id = Auth::user()->colleges->first()->college_id;

        $students = User::whereHas('roles', function ($query) {
            $query->where('role', env("ROLPROFE"));
        })
            ->whereHas('teachers.course', function ($query) use ($college_id) {
                $query->where('college_id', $college_id);
            })
            ->with('teachers.course')
            ->get();

        $data = [];
        foreach ($students as $key => $user) {
            foreach ($user->teachers as $teacher) {
                $data[] = [
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'telefono' => $user->phone,
                    'curso' => $teacher->course->name . ' ' . $teacher->course->section,
                    'direccion' => $teacher->address,
                    'status' => $user->status,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
}
