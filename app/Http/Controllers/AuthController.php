<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

        if (Session::has('user_email')) {
            Session::forget('user_email');
        }

        return view('login.index', compact('data'));
    }

    public function resetPassword()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Recuperar Password",
            'page_functions_js' => 'functions_password.js',
        ];

        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('login.resetPassword', compact('data'));
    }

    public function changePassword()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Recuperar Password",
            'page_functions_js' => 'functions_password.js',
        ];

        if (!session()->has('user_email')) {
            return redirect()->route('login');
        }

        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('login.setPassword', compact('data'));
    }

    public function login(Request $request)
    {
        $dni = $request->dni;
        $password = $request->password;

        $user = User::with('roles')->where('dni', $dni)->first();

        if ($user && Hash::check($password, $user->password)) {

            $imgPerfil = '';
            foreach ($user->roles as $role) {
                if ($role->role == env("ROLADMIN")) {
                    $imgPerfil = 'avatar5.png';
                    break;
                } else {
                    if ($role->role == env("ROLADMINCOLE")) {
                        $imgPerfil = 'avatarAdminCole.png';
                    } else if ($role->role == env("ROLPROFE")) {
                        $imgPerfil = 'avatar4.png';
                    } else {
                        $imgPerfil = 'avatarAlum.jpg';
                    }
                    // Obtiene el colegio asociado al usuario
                    $college = College::whereHas('usersCollege', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->first();
                    // Anida el colegio al usuario
                    $user->colleges = $college;
                    break;
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

    public function getEmailUser(Request $request)
    {
        $email = ucfirst($request->input('email'));

        $user = User::where('email', $email)->first();

        if ($user) {
            session(['user_email' => $user->email]);
            return response()->json([
                'success' => true,
                'msg' => 'Email verificado Exitosamente !!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'Email no encontrado',
            ]);
        }
    }

    public function setPassword(Request $request)
    {
        $email = session('user_email');

        // Verifica que el email esté en sesión
        if (!$email) {
            return redirect()->route('login');
        }

        // Busca al usuario por el email en sesión
        $user = User::with('roles')->where('email', $email)->first();

        if ($user) {
            // Actualiza la contraseña
            $password = bcrypt($request->input('txtPassword01'));
            $user->password = $password;
            $user->save();

            // Buscamos el rol del usuario y verificamos cual es su avatar 
            $imgPerfil = '';
            foreach ($user->roles as $role) {
                if ($role->role == env("ROLADMIN")) {
                    $imgPerfil = 'avatar5.png';
                    break;
                } else {
                    if ($role->role == env("ROLADMINCOLE")) {
                        $imgPerfil = 'avatarAdminCole.png';
                    } else if ($role->role == env("ROLPROFE")) {
                        $imgPerfil = 'avatar4.png';
                    } else {
                        $imgPerfil = 'avatarAlum.jpg';
                    }

                    // Obtiene el colegio asociado al usuario
                    $college = College::whereHas('usersCollege', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->first();

                    // Anida el colegio al usuario
                    $user->college = $college;
                    break;
                }
            }

            // Agregamos la imagen al objeto usuario
            $user->imgPerfil = $imgPerfil;

            session(['imgPerfil' => $imgPerfil]);

            // Inicia sesión con el usuario autenticado
            Auth::login($user);


            return response()->json([
                'success' => true,
                'userData' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
    }

}
