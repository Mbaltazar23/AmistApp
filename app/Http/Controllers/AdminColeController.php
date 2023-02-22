<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\UserRoles;
use App\Models\CollegeUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminColeController extends Controller
{
    //
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Admins Colegio",
            'page_title' => 'Administrador(s)/Colegios',
            'page_functions_js' => 'functions_admincolegio.js',
        ];

        return view('adminCole.index', compact('data'));
    }

    public function getAdmins()
    {
        $usersAdmins = User::whereHas('roles', function ($query) {
            $query->where('role', env("ROLADMINCOLE"));
        })->get();

        $data = [];
        foreach ($usersAdmins as $key => $user) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $btnSchool = '';
            $row = [
                'dni' => $user->dni,
                'nombre' => $user->name,
                'email' => $user->email,
                'telefono' => $user->phone,
            ];

            if ($user->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver administrador"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar administrador"><i class="fas fa-pencil-alt"></i></button>';
                $btnSchool = '<button class="btn btn-dark btn-sm" onClick="fntSchoolA(' . $user->id . ')" title="Vincular Colegio"><i class="fas fa-school"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $user->id . ')" title="Eliminar administrador"><i class="far fa-trash-alt"></i></button>';
            } else if ($user->status == 2) {
                $row['status'] = '<span class="badge badge-dark">Vinculado</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver administrador"><i class="far fa-eye"></i></button>';
                $btnSchool = '<button class="btn btn-dark btn-sm" onClick="fntSchoolU(' . $user->id . ')" title="Editar Colegio"><i class="fas fa-school"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar administrador"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelSchool(' . $user->id . ')" title="Eliminar Colegio"><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $user->id . ')" title="Ver administrador" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $user->id . ')" title="Editar administrador" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnSchool = '<button class="btn btn-secondary btn-sm" onClick="fntSchoolA(' . $user->id . ')" title="Vincular Colegio" disabled><i class="fas fa-school"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $user->id . ')" title="Activar administrador"><i class="fas fa-toggle-on"></i></button>';
            }
            $row['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnSchool . ' ' . $btnDelete . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setAdmin(Request $request)
    {
        $id = $request->input('idAdmin');
        $dni = $request->input('txtDni');
        $name = ucwords($request->input('txtNombre'));
        $email = ucwords($request->input('txtEmail'));
        $phone = $request->input('txtTelefono');
        $password = bcrypt(ucwords($name));
        $address = $request->input('txtDireccion');
        $rolA = env("ROLADMINCOLE");

        if ($id) {
            // actualizar usuario existente
            $user = User::find($id);
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->phone = $phone;
            if (!$address) {
                $user->address = '';
            } else {
                $user->address = $address;
            }
            $user->save();

            // actualizar el rol del usuario, pero solo si aún no existe un registro en la tabla user_roles
            if (!$user->roles()->where('role', $rolA)->exists()) {
                $userRole = new UserRoles();
                $userRole->role = $rolA;
                $user->roles()->save($userRole);
            }

            return response()->json(['status' => true, 'msg' => 'Usuario actualizado con éxito', 'data' => $user]);
        } else {
            // crear un nuevo usuario y asignar el rol correspondiente
            $user = new User();
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->phone = $phone;
            $user->remember_token = Str::random(10);

            if (!$address) {
                $user->address = '';
            } else {
                $user->address = $address;
            }
            $user->save();

            $userRole = new UserRoles();
            $userRole->role = $rolA;
            $user->roles()->save($userRole);

            return response()->json(['status' => true, 'msg' => 'Administrador registrado Exitosamente !!', 'data' => $user]);
        }
    }

    public function getAdmin($id)
    {
        $userAdmin = User::with('roles', 'colleges')
            ->where('id', $id)
            ->first();

        if ($userAdmin) {
            $collegeUserId = null;
            $collegeId = null;

            // Verifica si existe algún college asociado al usuario
            if ($userAdmin->colleges->isNotEmpty()) {
                $collegeUserId = $userAdmin->colleges->first()->id;
                $collegeId = $userAdmin->colleges->first()->college_id;
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $userAdmin->id,
                    'dni' => $userAdmin->dni,
                    'nombre' => $userAdmin->name,
                    'email' => $userAdmin->email,
                    'direccion' => $userAdmin->address,
                    'telefono' => $userAdmin->phone,
                    'fecha' => $userAdmin->created_at->format('d-m-Y'),
                    'hora' => $userAdmin->created_at->format('H:i:s'),
                    'status' => $userAdmin->status,
                    'idColegioUser' => $collegeUserId,
                    'colegio_id' => $collegeId,
                ],
                'msg' => 'Categoría obtenida correctamente',
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

        $userAdmin = User::find($id);

        if (!$userAdmin) {
            return response()->json(['status' => false, 'msg' => 'El Administrador no existe']);
        }

        if ($status == 0) {
            $hasCollege = $userAdmin->colleges()->exists();
            if ($hasCollege) {
                return response()->json(['status' => false, 'msg' => 'Este Administrador ya esta a cargo de un Colegio', 'data' => $userAdmin]);
            }
        }

        $userAdmin->status = $status;
        $userAdmin->save();

        if ($status == 1) {
            $message = 'Administrador Habilitado Exitosamente !!';
        } else {
            $message = 'Administrador Inhabilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function setCollegeAdmin(Request $request)
    {
        $userId = $request->input("idAdminC");
        $collegeId = $request->input("listColegios");
        $collegeUserId = $request->input('idVinCol');

        // Check if the college is already associated with the user
        $user = User::with('colleges')->find($userId);
        if ($user->colleges->contains('college_id', $collegeId)) {
            return response()->json(['status' => false, 'msg' => '¡Atención! El colegio ya existe.']);
        }

        if ($collegeUserId) {
            $collegeUser = CollegeUser::find($collegeUserId);
            $collegeUser->college_id = $collegeId;
            $collegeUser->user_id = $userId;
            $collegeUser->save();

            return response()->json(['status' => true, 'msg' => 'Colegio actualizado Exitosamente !!']);
        } else {
            $collegeUser = new CollegeUser;
            $collegeUser->college_id = $collegeId;
            $collegeUser->user_id = $userId;
            $collegeUser->save();
            User::where('id',  $userId)->update(['status' => 2]);

            return response()->json(['status' => true, 'msg' => 'Colegio vinculado Exitosamente !!']);
        }

    }

    public function deleteCollegeAdmin($id)
    {
        // Obtener el college del usuario
        $collegeUser = CollegeUser::where('user_id', $id)->first();
        // Verificar si hay cursos asociados al college
        $hasCourses = Course::where('college_id', $collegeUser->college_id)->exists();

        if (!$hasCourses) {
            // Eliminar el registro de la tabla college_users
            $collegeUser->delete();

            // Actualizar el estado del usuario
            User::where('id', $collegeUser->user_id)->update(['status' => 1]);

            return response()->json(['status' => true, 'msg' => 'Colegio desvinculado exitosamente']);
        } else {
            return response()->json(['status' => false, 'msg' => 'El Colegio tiene cursos asociados']);
        }
    }

    public function getReport()
    {
        $rol = env('ROLADMINCOLE');
        $usersAdmins = User::whereHas('roles', function ($query) use ($rol) {
            $query->where('role', $rol);
        })->orWhereHas('colleges')->get();

        return response()->json([
            'data' => $usersAdmins,
        ]);
    }
}
