<?php

namespace App\Http\Controllers;

use App\Models\CollegeUser;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    //
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Alumnos",
            'page_title' => 'Alumnos',
            'page_functions_js' => 'functions_alumns.js',
        ];

        return view('students.index', compact('data'));
    }

    public function getStudents()
    {
        $college_id = Auth::user()->colleges->first()->college_id;

        $students = User::whereHas('roles', function ($query) {
            $query->where('role', env("ROLALU"));
        })
            ->whereHas('students.course', function ($query) use ($college_id) {
                $query->where('college_id', $college_id);
            })
            ->with('students.course')
            ->get();

        $data = [];
        foreach ($students as $key => $user) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            foreach ($user->students as $student) {
                $row = [
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'telefono' => $user->phone,
                    'curso' => $student->course->name . ' ' . $student->course->section,
                ];
                if ($user->status == 1) {
                    $row['status'] = '<span class="badge badge-success">Activo</span>';
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Alumno"><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Alumno"><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $user->id . ')" title="Eliminar Alumno"><i class="far fa-trash-alt"></i></button>';
                } else {
                    $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                    $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Alumno" disabled><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Alumno" disabled><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $user->id . ')" title="Activar Alumno"><i class="fas fa-toggle-on"></i></button>';
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

    public function setStudent(Request $request)
    {
        $id = $request->input('idAlumn');
        $dni = $request->input('txtRutAlu');
        $name = ucwords($request->input('txtNombres'));
        $email = ucfirst($request->input('txtCorreoAlu'));
        $course = $request->input('listCurso');
        $phone = $request->input('txtTelefono');
        $address = $request->input('txtDireccion');
        $password = bcrypt("1234.");
        $rol = env('ROLALU');
        $college_id = Auth::user()->colleges->first()->college_id;

        if (empty($id)) {
            // Verificar si el usuario ya está registrado por su DNI
            $existingUser = User::where('dni', $dni)->first();
            if ($existingUser) {
                return response()->json(['status' => false, 'msg' => 'El Alumno ya está registrado', 'data' => null]);
            }

            // Crear un nuevo usuario y asignar el rol RolALU
            $user = new User();
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->points = 100;
            if (!$address) {
                $user->address = '';
            } else {
                $user->address = $address;
            }
            $user->password = $password;
            $user->remember_token = Str::random(10);
            $user->save();

            $userRole = new UserRoles();
            $userRole->role = $rol;
            $user->roles()->save($userRole);

            // Verificar si el curso tiene un profesor asignado
            $courseInfo = Course::with('teachers')->find($course);
            if ($courseInfo->teachers->isNotEmpty()) {
                // Asignar al curso
                $student = new Student();
                $student->user_id = $user->id;
                $student->course_id = $course;
                $student->remember_token = Str::random(10);
                $user->students()->save($student);

                // Asignar el colegio al usuario
                $collegeUser = new CollegeUser();
                $collegeUser->college_id = $college_id;
                $collegeUser->user_id = $user->id;
                $collegeUser->save();

                return response()->json(['status' => true, 'msg' => 'Usuario registrado con éxito y asignado al curso', 'data' => $user]);
            } else {
                // Eliminar usuario si no hay un profesor asignado al curso
                $user->delete();

                return response()->json(['status' => false, 'msg' => 'El Curso no tiene un profesor asignado']);
            }
        } else {
            // Actualizar usuario existente
            $user = User::find($id);
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            if (!$address) {
                $user->address = '';
            } else {
                $user->address = $address;
            }
            $user->password = $password;
            $user->save();

            // Verificar si el curso ha cambiado
            $existingStudent = $user->students()->where('course_id', $course)->first();
            if ($existingStudent) {
                // El estudiante ya está asignado al curso, no hay que hacer nada más
                return response()->json(['status' => true, 'msg' => 'Alumno actualizado Exitosamente !!', 'data' => $user]);
            } else {
                // Verificar si el curso tiene un profesor asignado
                $courseInfo = Course::with('teachers')->find($course);
                if ($courseInfo->teachers->isNotEmpty()) {
                    // Asignar al nuevo curso
                    $student = new Student();
                    $student->user_id = $user->id;
                    $student->course_id = $course;
                    $user->students()->save($student);
                    // Eliminar al estudiante del curso anterior
                    $existingStudent->delete();

                    return response()->json(['status' => true, 'msg' => 'Alumno actualizado y reasignado a nuevo curso', 'data' => $user]);
                } else {
                    // No hay profesor asignado, no se puede asignar el curso
                    return response()->json(['status' => false, 'msg' => 'El Curso no tiene un profesor asignado']);
                }
            }
        }
    }

    public function getStudent($id)
    {
        $student = User::where('id', $id)
            ->whereHas('roles', function ($query) {
                $query->where('role', env("ROLALU"));
            })
            ->with('students.course')
            ->first();
        if ($student) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $student->id,
                    'dni' => $student->dni,
                    'nombre' => $student->name,
                    'correo' => $student->email,
                    'direccion' => $student->address,
                    'telefono' => $student->phone,
                    'fecha' => $student->created_at->format('d-m-Y'),
                    'hora' => $student->created_at->format('H:i:s'),
                    'status' => $student->status,
                    'idCurso' => $student->students[0]->course->id,
                    'curso' => $student->students[0]->course->name . ' ' . $student->students[0]->course->section,
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

    public function setStatus($id, Request $request)
    {
        $status = $request->input('status');

        $student = User::find($id);

        if (!$student) {
            return response()->json(['status' => false, 'msg' => 'El Alumno no existe']);
        }

        if ($status == 0) {
            $hasPointsActions = $student->pointsUserActions()->exists();
            $hasPointsActionsSend = $student->pointsUserActionsSent()->exists();
            if ($hasPointsActions || $hasPointsActionsSend) {
                return response()->json(['status' => false, 'msg' => 'Este Alumno tiene su registro de puntaje en uso', 'data' => $student]);
            }
        }

        $student->status = $status;
        $student->save();

        if ($status == 1) {
            $message = 'Alumno Habilitado Exitosamente !!';
        } else {
            $message = 'Alumno Inhabilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function getReport()
    {
        $college_id = Auth::user()->colleges->first()->college_id;

        $students = User::whereHas('roles', function ($query) {
            $query->where('role', env("ROLALU"));
        })
            ->whereHas('students.course', function ($query) use ($college_id) {
                $query->where('college_id', $college_id);
            })
            ->with('students.course')
            ->get();

        $data = [];
        foreach ($students as $key => $user) {
            foreach ($user->students as $student) {
                $data[] = [
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'telefono' => $user->phone,
                    'curso' => $student->course->name . ' ' . $student->course->section,
                    'direccion' => $user->address,
                    'status' => $user->status,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
}
