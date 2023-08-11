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

    public function allTeachers()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Profesores",
            'page_title' => 'Profesores',
            'page_functions_js' => 'functions_all_teachers.js',
        ];

        return view('teachers.teachers_all', compact('data'));
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
                    return '-' . '<strong>' . $course->name . '</strong>  ' . $course->section;
                })->implode('<br>'); // Cambiar \n por <br> para salto de línea HTML

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
                $row['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . ' ' . $btnAllDelete . '</div>';
                $data[] = $row;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);

    }

    public function getAllTeachers()
    {
        $teachers = User::with('teachers.courses.college', 'teachers')->whereHas('teachers')->get();

        $data = [];
        foreach ($teachers as $key => $user) {
            $btnChangePassword = '';
            $btnSetPoints = "";
            $btnDisableAccount = '';

            $courses = [];
            foreach ($user->teachers as $teacher) {
                foreach ($teacher->courses as $course) {
                    $collegeName = $course->college->name;
                    $courseInfo = '<strong>' . $course->name . '</strong>  ' . $course->section;
                    $courses[] = $courseInfo;
                }
                $row = [
                    'dni' => $user->dni,
                    'profesor' => '<strong>Nombres:</strong> ' . $user->name . '<br><strong>Email:</strong> ' . $user->email . '<br><strong>Teléfono:</strong> ' . $user->phone,
                    'cursos' => implode('<br>', $courses), // Convertir el array de cursos en una cadena
                    'colegio' => $collegeName, // No seguro si esta variable es correcta aquí, revisa tu lógica
                ];
                if ($user->status == 1) {
                    $row['status'] = '<span class="badge badge-success">Activo</span>';
                    $btnSetPoints = '<button class="btn btn-dark btn-sm" onClick="fntSetPoints(' . $user->id . ')" title="Asignar Puntaje"><i class="fas fa-star"></i></button>';
                    $btnChangePassword = '<button class="btn btn-primary btn-sm" onClick="fntChangePassword(' . $user->id . ')" title="Cambiar Contraseña"><i class="fas fa-key"></i></button>';
                    $btnDisableAccount = '<button class="btn btn-danger btn-sm" onClick="fntDisableAccount(' . $user->id . ')" title="Ocultar/Inhabilitar Cuenta"><i class="fas fa-user-times"></i></button>';
                } else {
                    $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                    $btnSetPoints = '<button class="btn btn-secondary btn-sm" onClick="fntSetPoints(' . $user->id . ')" title="Asignar Puntaje" disabled><i class="fas fa-star"></i></button>';
                    $btnChangePassword = '<button class="btn btn-secondary btn-sm" onClick="fntChangePassword(' . $user->id . ')" title="Cambiar Contraseña" disabled><i class="fas fa-key"></i></button>';
                    $btnDisableAccount = '<button class="btn btn-secondary btn-sm" onClick="fntEnableAccount(' . $user->id . ')" title="Mostrar/Habilitar Cuenta"><i class="fas fa-user-check"></i></button>';
                }
                $row['options'] = '<div class="text-center">' . $btnSetPoints . ' ' . $btnChangePassword . ' ' . $btnDisableAccount . '</div>';
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
                    'correo' => $teacher->email,
                    'direccion' => $teacher->address,
                    'telefono' => $teacher->phone,
                    'fecha' => $teacher->created_at->format('d-m-Y'),
                    'hora' => $teacher->created_at->format('H:i:s'),
                    'status' => $teacher->status,
                    'cursos' => $courses,
                    'puntos' => $teacher->points,
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

    public function setPasswordTeacher(Request $request)
    {
        $id = $request->input('idTeacher');
        $password = $request->input('txtPassword01');

        try {
            $user = User::findOrFail($id);
            $user->password = bcrypt($password);
            $user->save();

            return response()->json([
                'status' => true,
                'msg' => 'Contraseña actualizada exitosamente.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Error al actualizar la contraseña.' . $e,
            ]);
        }
    }

    public function setPointsTeacher(Request $request)
    {
        $id = $request->input('idTeacherPoint');
        $points = $request->input('txtPuntaje');

        try {
            $user = User::findOrFail($id);
            $user->points = $points;
            $user->save();

            return response()->json([
                'status' => true,
                'msg' => 'Puntaje asignado exitosamente !!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Error al asignar el puntaje.' . $e,
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

        if ($status == 1) {
            $message = 'Profesor Habilitado Exitosamente !!';
        } else {
            $message = 'Profesor Inhabilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function deleteTeacher($id)
    {
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

        $teachers = User::whereHas('roles', function ($query) {
            $query->where('role', 'Profesor');
        })->whereHas('teachers.courses', function ($query) use ($college_id) {
            $query->where('college_id', $college_id);
        })->with('teachers.courses')->get();

        $data = [];
        foreach ($teachers as $key => $user) {
            foreach ($user->teachers as $teacher) {
                $courses = $teacher->courses->map(function ($course) {
                    return '-' . '<strong>' . $course->name . '</strong>  ' . $course->section;
                })->implode('<br>'); // Cambiar \n por <br> para salto de línea HTML

                $data[] = [
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'telefono' => $user->phone,
                    'cursos' => $courses,
                    'direccion' => $teacher->address,
                    'status' => $user->status,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    public function getAllReport()
    {
        $teachers = User::with('teachers.courses.college', 'teachers')->whereHas('teachers')->get();

        $data = [];
        foreach ($teachers as $key => $user) {
            $courses = [];
            foreach ($user->teachers as $teacher) {
                $profesor = [
                    'Nombres' => $user->name,
                    'Email' => $user->email,
                    'Teléfono' => $user->phone,
                ];
                foreach ($teacher->courses as $course) {
                    $collegeName = $course->college->name;
                    $courseInfo =   $course->name . ' ' . $course->section;
                    $courses[] = $courseInfo;
                }
                $data[] = [
                    'dni' => $user->dni,
                    'profesor' => $profesor,
                    'colegio' => $collegeName,
                    'cursos' => $courses,
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
