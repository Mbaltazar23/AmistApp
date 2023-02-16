<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Dashboard",
            'page_functions_js' => 'functions_dashboard.js',
        ];

        if (!Auth::check()) {
            return view('login', compact('data'));
        }
    
        return view('dashboard.index', compact('data'));
    }

    public function show()
    {
        $data = [
            'page_tag' =>  "Dashboard - Perfil",
            'page_functions_js' => 'functions_perfil.js',
        ];
       
        return view('dashboard.profile', compact('data'));
    }

    public function darFormatoFecha($fechaTex)
    {
        $fecha = substr($fechaTex, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return $nombreMes . " de " . $anio;
    }

    public function navDashboardAdmin()
    {
        $navAdmin = "";
        foreach (Auth::user()->roles as $role) {
            if ($role->role == 'Administrador') {
                $navAdmin = array(
                    "Canjeo de Puntos" => array(
                        "icon" => "fas fa-regular fa-award",
                        "submodulos" => array(
                            "Acciones" => array("pagina" => "acciones"),
                            "Notificaciones" => array("pagina" => "notificaciones"),
                        ),
                    ),
                    "Educacion" => array(
                        "icon" => "fas fa-solid fa-school",
                        "submodulos" => array(
                            "Adminstrador(s) Colegio" => array("pagina" => "admin-colegio"),
                            "Colegios" => array("pagina" => "colegios")
                        ),
                    ),
                    "Catalogo" => array(
                        "icon" => "fas fa-solid fa-store",
                        "submodulos" => array(
                            "Categorias" => array("pagina" => "categorias"),
                            "Productos" => array("pagina" => "productos"),
                        ),
                    ),
                );
            }
        }
        return $navAdmin;
    }
}
