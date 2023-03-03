<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\UserRoles;
use App\Models\CollegeUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $query->where('role', env("ROLPROFE"));
        })->whereHas('teachers.course', function ($query) use ($college_id) {
            $query->where('college_id', $college_id);
        })
            ->with('teachers.course')
            ->get();

        $data = [];
        foreach ($teachers as $key => $user) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            foreach ($user->teachers as $teacher) {
                $row = [
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'curso' => $teacher->course->name . ' ' . $teacher->course->section,
                ];
                if ($user->status == 1) {
                    $row['status'] = '<span class="badge badge-success">Activo</span>';
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Profesor"><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Profesor"><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $user->id . ')" title="Eliminar Profesor"><i class="far fa-trash-alt"></i></button>';
                } else {
                    $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                    $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Profesor" disabled><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Profesor" disabled><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $user->id . ')" title="Activar Profesor"><i class="fas fa-toggle-on"></i></button>';
                }
                $row['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
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
        $course = $request->input('listCurso');
        $phone = $request->input('txtTelefono');
        $address = $request->input('txtDireccion');
        $password = bcrypt('AmistApp.');
        $college_id = Auth::user()->colleges->first()->college_id;
        $rol = env("ROLPROFE");

        if ($id) {
            // actualizamos los datos del profesor
            $user = User::find($id);
            $teacher = $user->teachers()->first();

            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->password = $password;
            $user->address = $address ? $address : '';
            $user->save();

            //dd($teacher->course_id);
            // Verificar si se cambió el curso asociado al teacher
            if ($teacher->course_id != $course) {
                // Verificar si el nuevo curso ya tiene un teacher asignado
                $new_course = Course::find($course);
                if ($new_course->teachers->count() == 0) {
                    // Actualizar el registro de Teacher
                    $teacher->course_id = $course;
                    $teacher->save();
                    return response()->json(['status' => true, 'msg' => 'Profesor actualizado Exitosamente !!']);
                } else {
                    // Devolver un mensaje de error
                    return response()->json(['status' => false, 'msg' => 'El Curso seleccionado ya tiene un profesor asignado.']);
                }
            } else {
                return response()->json(['status' => true, 'msg' => 'Profesor actualizado Exitosamente !!']);
            }
        } else {
            // Insertar un nuevo registro del profesor
            $user = new User();
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->address = $address ? $address : '';
            $user->password = $password;
            $user->points = 500;
            $user->remember_token = Str::random(10);
            $user->save();

            // Verificar si el curso seleccionado ya tiene un teacher asignado
            $new_course = Course::find($course);
            if ($new_course->teachers->count() == 0) {
                // Crear un nuevo registro de Teacher
                $teacher = new Teacher();
                $teacher->user_id = $user->id;
                $teacher->course_id = $course;
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

                return response()->json(['status' => true, 'msg' => 'Profesor registrado Exitosamente !!']);
            } else {
                // Eliminar el registro de User y CollegeUser
                $user->delete();

                return response()->json(['status' => false, 'msg' => 'El Curso seleccionado ya tiene un profesor asignado.']);
            }
        }
    }

    public function getTeacher($id)
    {
        $teacher = User::where('id', $id)
            ->whereHas('roles', function ($query) {
                $query->where('role', env("ROLPROFE"));
            })
            ->with('teachers.course')
            ->first();
        if ($teacher) {
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
                    'idCurso' => $teacher->teachers[0]->course->id,
                    'curso' => $teacher->teachers[0]->course->name . ' ' . $teacher->teachers[0]->course->section,
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
            if ($course && $course->students()->whereHas('user', function($query) {
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
