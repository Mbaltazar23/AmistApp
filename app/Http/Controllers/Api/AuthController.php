<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $dni = $request->dni;
        $password = $request->password;

        $user = User::with('roles')->where('dni', $dni)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Inicializa la imagen de perfil en blanco
            $imgPerfil = '';

            // Busca el rol del usuario y establece la imagen de perfil correspondiente
            switch ($user->roles->first()->role) {
                case env("ROLADMIN"):
                    $imgPerfil = 'avatar5.png';
                    break;
                case env("ROLADMINCOLE"):
                    $imgPerfil = 'avatarAdminCole.png';
                    break;
                case env("ROLPROFE"):
                    $imgPerfil = 'avatar4.png';
                    break;
                default:
                    $imgPerfil = 'avatarAlum.jpg';
                    break;
            }

            // Obtiene el colegio asociado al usuario
            $college = College::whereHas('usersCollege', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

            // Anida el colegio al usuario
            $user->college = $college ? $college : null;

            // Agregamos la imagen al objeto usuario
            $user->imgPerfil = $imgPerfil;

            // Inicia sesión con el usuario autenticado
            Auth::login($user);

            session(['imgPerfil' => $imgPerfil]);

            return response()->json([
                'success' => true,
                'userData' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
    }

}
