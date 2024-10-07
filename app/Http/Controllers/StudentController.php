<?php

namespace App\Http\Controllers;

use App\Models\College;
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
            'page_tag' => env("NOMBRE_WEB") ." - Alumnos",
            'page_title' => 'Alumnos',
            'page_functions_js' => 'functions_alumns.js',
        ];

        return view('students.index', compact('data'));
    }

    public function allStudents()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Alumnos",
            'page_title' => 'Alumnos',
            'page_functions_js' => 'functions_all_alumns.js',
        ];

        return view('students.students_all', compact('data'));
    }

    public function getStudents()
    {
        $college_id = Auth::user()->colleges->first()->college_id;

        $students = User::whereHas('roles', function ($query) {
            $query->where('role', "Alumno");
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
            $btnAllDelete = '';
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
                    $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $user->id . ')" title="Inhabilitar Alumno"><i class="far fa-trash-alt"></i></button>';
                    $btnAllDelete = '<button class="btn btn-secondary btn-sm" onClick="fntDelAll(' . $user->id . ')" title="Eliminar Alumno" disabled><i class="far fa-trash-alt"></i></button>';
                } else {
                    $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                    $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver Alumno" disabled><i class="far fa-eye"></i></button>';
                    $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar Alumno" disabled><i class="fas fa-pencil-alt"></i></button>';
                    $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $user->id . ')" title="Activar Alumno"><i class="fas fa-toggle-on"></i></button>';
                    $btnAllDelete = '<button class="btn btn-dark btn-sm" onClick="fntDelAll(' . $user->id . ')" title="Eliminar Alumno"><i class="far fa-trash-alt"></i></button>';
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

    public function getAllStudents()
    {
        $students = User::with('students.course', 'colleges.college')->whereHas('students')->get();

        $data = [];
        foreach ($students as $key => $user) {
            $btnChangePassword = '';
            $btnSetPoints = "";
            $btnDisableAccount = '';
            foreach ($user->students as $student) {
                $collegeName = $student->course->college->name;
                $row = [
                    'dni' => $user->dni,
                    'alumno' => '<strong>Nombres:</strong> ' . $user->name . '<br><strong>Email:</strong> ' . $user->email . '<br><strong>Teléfono:</strong> ' . $user->phone,
                    'curso' => $student->course->name . ' ' . $student->course->section,
                    'colegio' => $collegeName,
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

    public function setStudent(Request $request)
    {
        // Obtener el valor actual de stock_alumns del colegio
        $college_id = Auth::user()->colleges->first()->college_id;
        $college = College::find($college_id);
        $currentStock = $college->stock_alumns;

        // Verificar si agregar un nuevo estudiante supera el límite de stock_alumns
        if ($currentStock <= 0) {
            // Si el stock es igual o menor que cero, no se puede insertar más estudiantes.
            // Aquí puedes lanzar una excepción, retornar un mensaje de error, o tomar cualquier otra acción según tus necesidades.
            return response()->json(['status' => false, 'msg' => 'El stock de alumnos para este colegio ha sido superado', 'data' => null]);
        }

        $id = $request->input('idAlumn');
        $dni = $request->input('txtRutAlu');
        $name = ucwords($request->input('txtNombres'));
        $email = ucfirst($request->input('txtCorreoAlu'));
        $course = $request->input('listCurso');
        $phone = $request->input('txtTelefono');
        $address = $request->input('txtDireccion');
        $password = bcrypt("AmistApp.");
        $rol = env('ROLALU');

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
            $userRole->remember_token = Str::random(10);
            $user->roles()->save($userRole);

            // Verificar si el curso tiene un profesor asignado
            $courseInfo = Course::with('teachers')->find($course);
            if ($courseInfo->teachers->isNotEmpty()) {
                // Asignar al curso
                $student = new Student();
                $student->user_id = $user->id;
                $student->course_id = $course;
                $user->students()->save($student);

                // Asignar el colegio al usuario
                $collegeUser = new CollegeUser();
                $collegeUser->college_id = $college_id;
                $collegeUser->user_id = $user->id;
                $collegeUser->save();
                $user->colleges()->save($collegeUser);

                return response()->json(['status' => true, 'msg' => 'Alumno registrado Exitosamente !!', 'data' => $user]);
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

            $student = Student::where('user_id', $id)->first();

            if (!$student) {
                return response()->json(['status' => false, 'msg' => 'El Alumno no está asignado a ningún curso', 'data' => null]);
            }

            // Si ya tiene el curso asignado, no hay que hacer nada
            if ($student->course_id == $course) {
                return response()->json(['status' => true, 'msg' => 'Alumno actualizado Exitosamente !!', 'data' => $user]);
            }

            // buscar curso por id
            $courseInfo = Course::with('teachers')->find($course);

            // si el curso existe y tiene profesor asignado, actualizar registro
            if ($courseInfo && $courseInfo->teachers->isNotEmpty()) {
                $student->course_id = $course;
                $student->save();
                return response()->json(['status' => true, 'msg' => 'Alumno actualizado Exitosamente !!', 'data' => $user]);
            } else {
                return response()->json(['status' => false, 'msg' => 'El Curso no existe o no tiene un profesor asignado']);
            }
        }
    }

    public function setStudents(Request $request)
    {
        $rol = env('ROLALU');
        $college_id = Auth::user()->colleges->first()->college_id;
        $listCursos = $request->input('listCursos');
        $users = $request->input('users');

        foreach ($users as $user) {
            $dni = $user['dni'];
            $existingUser = User::where('dni', $dni)->first();

            if ($existingUser) {
                continue; // Si el usuario ya existe, pasamos al siguiente
            }

            // Obtener el valor actual de stock_alumns del colegio
            $college = College::find($college_id);
            $currentStock = $college->stock_alumns;

            // Verificar si agregar un nuevo estudiante supera el límite de stock_alumns
            if ($currentStock <= 0) {
                // Si el stock es igual o menor que cero, no se puede insertar más estudiantes.
                // Aquí puedes lanzar una excepción, retornar un mensaje de error, o tomar cualquier otra acción según tus necesidades.
                return response()->json(['status' => false, 'msg' => 'El stock de alumnos para este colegio ha sido superado']);
            }

            // Crear un nuevo usuario y asignar los datos
            $newUser = new User();
            $newUser->dni = $dni;
            $newUser->name = ucwords($user['nombre']);
            $newUser->email = ucfirst($user['email']);
            $newUser->phone = "+56" . $user['telefono'];
            $newUser->address = $user['direccion'] ?? '';
            $newUser->password = bcrypt($user['password']) ?? bcrypt("AmistApp.");
            $newUser->remember_token = Str::random(10);
            $newUser->save();

            //Asignar Rol
            $userRole = new UserRoles();
            $userRole->role = $rol;
            $userRole->remember_token = Str::random(10);
            $newUser->roles()->save($userRole);

            // Asignar al curso
            $student = new Student();
            $student->user_id = $newUser->id;
            $student->course_id = $listCursos;
            $student->save();

            // Asignar el colegio al usuario
            $collegeUser = new CollegeUser();
            $collegeUser->college_id = $college_id;
            $collegeUser->user_id = $newUser->id;
            $collegeUser->save();
            $newUser->colleges()->save($collegeUser);

            // Reducir el stock_alumns actual
            $college->stock_alumns = $currentStock - 1;
            $college->save();
        }

        // Devolver respuesta exitosa
        return response()->json(['status' => true, 'msg' => 'Datos guardados exitosamente']);
    }

    public function setPasswordStudent(Request $request)
    {
        $id = $request->input('idAlumn');
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

    public function setPointsStudent(Request $request)
    {
        $id = $request->input('idAlumnPoint');
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
                    "puntos" => $student->points,
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

    public function deleteAlum($id)
    {
        $student = User::find($id);
        $college_id = Auth::user()->colleges->first()->college_id;

        if (!$student) {
            return response()->json(['status' => false, 'msg' => 'El Alumno no existe']);
        }

        $student->delete();

        // Incrementar el stock_alumns en 1 para el college correspondiente
        $college = College::find($college_id);
        $college->stock_alumns += 1;
        $college->save();

        return response()->json(['status' => true, 'msg' => 'Alumno Eliminado Exitosamente !!']);
    }

    public function getAllReport()
    {
        $students = User::with('students.course', 'colleges.college')->whereHas('students')->get();

        $data = [];
        foreach ($students as $key => $user) {
            foreach ($user->students as $student) {
                $collegeName = $student->course->college->name;
                $alumno = [
                    'Nombres' => $user->name,
                    'Email' => $user->email,
                    'Teléfono' => $user->phone,
                ];
                $data[] = [
                    'dni' => $user->dni,
                    'alumno' => $alumno,
                    'curso' => $student->course->name . ' ' . $student->course->section,
                    'direccion' => $user->address,
                    "status" => $user->status,
                    'colegio' => $collegeName,
                ];
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
