<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Inicio",
            'page_functions_js' => 'functions_home.js',
        ];

        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('login', compact('data'));
    }

    public function login(Request $request)
    {
        $dni = $request->dni;
        $password = $request->password;

        $user = User::with('roles')->where('dni', $dni)->first();

        if ($user && Hash::check($password, $user->password)) {

            $imgPerfil = '';
            foreach ($user->roles as $role) {
                if ($role->role == 'Administrador') {
                    $imgPerfil = 'avatar5.png';
                    break;
                } else if ($role->role == 'Profesor') {
                    $imgPerfil = 'avatar5.png';
                    break;
                } else {
                    $imgPerfil = 'avatarAlum.jpg';
                }
            }

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
