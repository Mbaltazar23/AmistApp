<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\College;
use App\Models\Notification;
use App\Models\PointAlumnAction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cardsPanel = $this->cardsPanelDashboard();
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Dashboard",
            'page_functions_js' => 'functions_dashboard.js',
        ];
        if (!Auth::check()) {
            return view('login', compact('data', 'cardsPanel'));
        }

        return view('dashboard.index', compact('data', 'cardsPanel'));
    }

    public function show()
    {
        $data = [
            'page_tag' => "Dashboard - Perfil",
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
            if ($role->role == env("ROLADMIN")) {
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
                            "Colegios" => array("pagina" => "colegios"),
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
            }else if ($role->role == env("ROLADMINCOLE")) {
                $navAdmin = array(
                    "Educacion" => array(
                        "icon" => "fas fa-regular fa-award",
                        "submodulos" => array(
                            "Cursos" => array("pagina" => "cursos"),
                            "Alumnos" => array("pagina" => "alumnos"),
                            "Profesores" => array("pagina" => "profesores"),
                        )
                    ),
                    "Catalogo" => array(
                        "icon" => "fas fa-solid fa-school",
                        "submodulos" => array(
                            "Productos" => array("pagina" => "productos")
                        )
                    )
                );
            }
        }
        return $navAdmin;
    }

    public function cardsPanelDashboard()
    {
        $cardsPanel = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->role == env("ROLADMIN")) {
                //Cantidad de Acciones mas usadas por alumnos:
                $totalActions = Action::count();

                $topAction = Action::withCount('pointsUserActions')
                    ->orderBy('points_user_actions_count', 'desc')
                    ->first();

                $percentageActions = ($topAction->points_user_actions_count / $totalActions) * 100;

                //% de puntaje dado por alumnos entre ellos:
                $total_points = PointAlumnAction::sum('points');

                $student_points = PointAlumnAction::where('user_send_id', '<>', 'user_recept_id')
                    ->sum('points');

                $student_points_percentage = 0;

                if ($total_points > 0) {
                    $student_points_percentage = ($student_points / $total_points) * 100;
                }

                //Cantidad de Colegios registrados:
                $registered_colleges = College::whereHas('courses')
                ->withCount('courses')
                ->count();
                //% de notificaciones más vistas:
                $total_notifications = Notification::count();

                $most_viewed_notifications = Notification::withCount('usersNotifications')
                    ->with('usersNotifications')
                    ->orderBy('users_notifications_count', 'desc')
                    ->take(10)
                    ->get();

                $most_viewed_notifications_percentage = ($most_viewed_notifications->sum(function ($notification) {
                    return $notification->usersNotifications->unique('user_id')->count();
                }) / $total_notifications) * 100;

                $cardsPanel = array(
                    "top_actions" => array(
                        "title" => "Acciones más usadas por alumnos",
                        "icon" => "fas fa-award",
                        "color" => "bg-warning",
                        "value" => $percentageActions,
                        "url" => "acciones",
                    ),
                    "student_points_percentage" => array(
                        "title" => "% de puntos entre Alumnos",
                        "icon" => "fas fa-users",
                        "color" => "bg-success",
                        "value" => $student_points_percentage,
                        "url" => "acciones",
                    ),
                    "registered_colleges" => array(
                        "title" => "Cantidad de colegios en uso",
                        "icon" => "fas fa-school",
                        "color" => "bg-blue",
                        "value" => $registered_colleges,
                        "text" => "colegios registrados",
                        "url" => "/colegios",
                    ),
                    "% of notifications" => array(
                        "title" => "% de notificaciones mas usadas",
                        "icon" => "fas fa-chart-bar",
                        "color" => "bg-dark",
                        "value" => $most_viewed_notifications_percentage . "%",
                        "text" => "% de notificaciones",
                        "url" => "/notificaciones",
                    ),
                );

            }
        }
        return $cardsPanel;
    }
}
