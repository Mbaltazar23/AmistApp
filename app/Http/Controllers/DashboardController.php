<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\College;
use App\Models\Course;
use App\Models\Notification;
use App\Models\PointAlumnAction;
use App\Models\Purchase;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cardsPanel = $this->cardsPanelDashboard();
        $page_tag = Auth::user()->roles->first()->role != env("ROLADMIN") ?
        env("NOMBRE_WEB") . "/" . env("NOMBRE_DASHBOARD") . ' - ' . Auth::user()->roles->first()->role :
        env("NOMBRE_WEB") . " - Dashboard";
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Dashboard",
            'page_title' => $page_tag,
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
            'page_tag' => env("NOMBRE_WEB") . " - Perfil",
            'page_functions_js' => 'functions_perfil.js',
        ];

        return view('dashboard.profile', compact('data'));
    }

    public function getProfile()
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $user->id,
                    'dni' => $user->dni,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'direccion' => $user->address ? $user->address : "",
                    'telefono' => $user->phone,
                    'fecha' => $user->created_at->format('d-m-Y'),
                    'hora' => $user->created_at->format('H:i:s'),
                    'status' => $user->status,
                ],
                'msg' => 'Usuario obtenido correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtener la categoría',
            ]);
        }
    }

    public function setProfile(Request $request)
    {
        $id = Auth::user()->id;
        $dni = $request->input('txtRut');
        $name = ucwords($request->input('txtNombre'));
        $email = $request->input('txtEmail');
        $phone = $request->input('txtTelefono');
        $address = ucfirst($request->input('txtDireccion'));
        $password = $request->input('txtPassword');

        $user = User::find($id);

        if ($user) {
            $user->dni = $dni;
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            if (!$address) {
                $user->address = '';
            } else {
                $user->address = $address;
            }
            if (!empty($password)) {
                $user->password = bcrypt($password);
            }
            $user->save();
            return response()->json(['status' => true, 'msg' => 'Datos Actualizados Exitosamente !!', 'data' => $user]);
        } else {
            return response()->json(['status' => false, 'msg' => 'No se encontro al Usuario']);
        }

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
            } else if ($role->role == env("ROLADMINCOLE")) {
                $navAdmin = array(
                    "Educacion" => array(
                        "icon" => "fas fa-user-graduate",
                        "submodulos" => array(
                            "Cursos" => array("pagina" => "cursos"),
                            "Alumnos" => array("pagina" => "alumnos"),
                            "Profesores" => array("pagina" => "profesores"),
                        ),
                    ),
                    "Catalogo" => array(
                        "icon" => "fas fa-solid fa-store",
                        "submodulos" => array(
                            "Productos" => array("pagina" => "productos-cat"),
                        ),
                    ),
                );
            } else if ($role->role == env("ROLALU")) {
                $navAdmin = array(
                    "Educacion" => array(
                        "icon" => "fas fa-regular fa-school",
                        "submodulos" => array(
                            "Compañeros" => array("pagina" => "alumnos"),
                        ),
                    ),
                    "Catalogo" => array(
                        "icon" => "fas fa-solid fa-store",
                        "submodulos" => array(
                            "Productos" => array("pagina" => "catalogo"),
                            "Mis Productos" => array("pagina" => "productos-adquiridos"),
                        ),
                    ),
                );
            } else if ($role->role == env("ROLPROFE")) {

            }
        }
        return $navAdmin;
    }

    public function cardsPanelDashboard()
    {
        $cardsPanel = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->role == env("ROLADMIN")) {
                //Cantidad de Acciones mas usadas por alumnos;
                $percentageActions = $this->actionsUsedAlumns();
                //% de puntaje dado por alumnos entre ellos:
                $student_points_percentage = $this->scoreThemStudents();
                //Cantidad de Colegios registrados:
                $registered_colleges = $this->countCollegeActives();
                //% de notificaciones más vistas:
                $most_viewed_notifications_percentage = $this->porcentageViewsNotifications();

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
                        "url" => "colegios",
                    ),
                    "% of notifications" => array(
                        "title" => "% de notificaciones mas usadas",
                        "icon" => "fas fa-chart-bar",
                        "color" => "bg-dark",
                        "value" => $most_viewed_notifications_percentage . "%",
                        "url" => "notificaciones",
                    ),
                );

            } else if ($role->role == env("ROLADMINCOLE")) {
                $collegeId = Auth::user()->colleges->first()->college_id;

                $porcentage_Students = $this->porcentajeStudentsCourse($collegeId);
                $countTeachers = $this->countsTeachersCourse($collegeId);
                $porcentage_productsPurchases = $this->porcentageProductsPurchases($collegeId);
                $percentActions = $this->porcentageStudentPerformActions($collegeId);

                $cardsPanel = array(
                    "porcentage alumns registred" => array(
                        "title" => "% de Alumnos registrados",
                        "icon" => "fas fa fa-users",
                        "color" => "bg-dark",
                        "value" => $porcentage_Students . "%",
                        "url" => "alumnos",
                    ),
                    "count teachers activates" => array(
                        "title" => "Profesores en uso",
                        "icon" => "fa fa-chalkboard-teacher",
                        "color" => "bg-success",
                        "value" => $countTeachers,
                        "url" => "profesores",
                    ),
                    "registered_colleges" => array(
                        "title" => "% de Productos mas adquiridos",
                        "icon" => "fas fa fa-shopping-cart",
                        "color" => "bg-blue",
                        "value" => $porcentage_productsPurchases . "%",
                        "url" => "productos",
                    ),
                    "% of notifications" => array(
                        "title" => "% de Acciones realizadas",
                        "icon" => "fas fa fa-check-circle",
                        "color" => "bg-warning",
                        "value" => $percentActions . "%",
                        "url" => "alumnos",
                    ),
                );
            } else if ($role->role == env("ROLALU")) {
                $idUser = Auth::user()->id;
                $pointsAvailable = $this->pointsAvailableAlum($idUser);
                $porcentageProductsPurchases = $this->porcentageNotificationsForAlum($idUser);
                $productosPurchases = $this->countProductsPurchasesAlum($idUser);

                $cardsPanel = array(
                    "points Alumn" => array(
                        "title" => "Puntos disponibles",
                        "icon" => "fas fa fa-star-half-alt",
                        "color" => "bg-dark",
                        "value" => $pointsAvailable,
                        "url" => "compañeros",
                    ),
                    "porcentage notifications for student" => array(
                        "title" => "% de Notificaciones contestadas",
                        "icon" => "fa fa-envelope-open-text",
                        "color" => "bg-success",
                        "value" => $porcentageProductsPurchases . "%",
                        "url" => "compañeros",
                    ),
                    "registered_colleges" => array(
                        "title" => "Cantidad de Productos adquiridos",
                        "icon" => "fas fa fa-cube",
                        "color" => "bg-blue",
                        "value" => $productosPurchases,
                        "url" => "productos-adquiridos",
                    ));
            }
        }
        return $cardsPanel;
    }

    // Cards panel Dashboard Admin
    public function actionsUsedAlumns()
    {
        $percentageActions = 0;
        $totalActions = Action::count();

        $topAction = Action::withCount('pointsUserActions')
            ->orderBy('points_user_actions_count', 'desc')
            ->first();

        if ($topAction) {
            $percentageActions = ($topAction->points_user_actions_count / $totalActions) * 100;
        } else {
            $percentageActions = 0;
        }
        return $percentageActions;
    }

    public function scoreThemStudents()
    {
        $student_points_percentage = 0;

        $total_points = PointAlumnAction::sum('points');

        $student_points = PointAlumnAction::where('user_send_id', '<>', 'user_recept_id')
            ->sum('points');

        if ($total_points > 0) {
            $student_points_percentage = ($student_points / $total_points) * 100;
        }
        return $student_points_percentage;
    }

    public function countCollegeActives()
    {
        $registered_colleges = College::whereHas('courses')
            ->withCount('courses')
            ->count();
        return $registered_colleges;
    }

    public function porcentageViewsNotifications()
    {
        $most_viewed_notifications_percentage = 0;
        //% de notificaciones más vistas:
        $total_notifications = Notification::where('status', 1)->count();

        $most_viewed_notifications = Notification::withCount('usersNotifications')
            ->with('usersNotifications')
            ->orderBy('users_notifications_count', 'desc')
            ->take(10)
            ->get();

        if ($total_notifications > 0) {
            $most_viewed_notifications_percentage = ($most_viewed_notifications->sum(function ($notification) {
                return $notification->usersNotifications->unique('user_id')->count();
            }) / $total_notifications) * 100;
        }
        return $most_viewed_notifications_percentage;
    }

    // Cards Panel of Dashboard : Admin Cole
    public function porcentajeStudentsCourse($collegeId)
    {
        $percentage = 0;
        $activeStatus = 1; // Este valor representa el estado de un estudiante activo

        $studentsCount = Student::whereHas('course.college', function ($query) use ($collegeId) {
            $query->where('id', $collegeId);
        })->whereHas('user', function ($query) use ($activeStatus) {
            $query->where('status', $activeStatus);
        })->count();

        $coursesCount = Course::where('college_id', $collegeId)->count();

        if ($coursesCount > 0) {
            $percentage = ($studentsCount / ($coursesCount * 30)) * 100; // Se asume que un curso tiene una duración de 30 días
        }

        return round($percentage, 2);
    }

    public function countsTeachersCourse($collegeId)
    {
        $teachersCount = Teacher::whereHas('course.college', function ($query) use ($collegeId) {
            $query->where('id', $collegeId);
        })
            ->whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->count();

        return $teachersCount;
    }

    public function porcentageProductsPurchases($collegeId)
    {
        $percentage = 0;
        $totalStock = Purchase::sum('stock'); // Stock total de todos los purchases

        $collegeStock = Purchase::whereHas('user.students.course.college', function ($q) use ($collegeId) {
            $q->where('id', $collegeId);
        })->sum('stock'); // Stock total de los purchases pertenecientes al colegio
        
        $percentage = ($collegeStock / $totalStock) * 100; // Porcentaje de los productos vendidos al colegio
        
        return $percentage;
    }

    public function porcentageStudentPerformActions($collegeId)
    {
        $percentActions = 0;
        $collegeId = Auth::user()->colleges->first()->college_id;

        $totalActions = PointAlumnAction::count();

        $userActions = PointAlumnAction::whereHas('userSend.colleges', function ($query) use ($collegeId) {
            $query->where('college_id', $collegeId);
        })
            ->orWhereHas('userRecept.colleges', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            })
            ->count();

        if ($totalActions > 0) {
            $percentActions = ($userActions / $totalActions) * 100;
        } else {
            $percentActions = 0;
        }
        return $percentActions;
    }

    //Cards Panel of Dashboard  : Alumn
    public function pointsAvailableAlum($idAlum)
    {
        $user = User::find($idAlum);
        $pointsAvailable = $user->points;
        return $pointsAvailable;
    }

    public function porcentageNotificationsForAlum($idAlum)
    {
        $percentAnswered = 0;
        $user = User::with('notifications')->find($idAlum);
        $totalNotifications = $user->notifications->count();
        $answeredNotifications = $user->notifications->where('status', 1)->count();
        if ($answeredNotifications > 0) {
            $percentAnswered = round(($answeredNotifications / $totalNotifications) * 100);
        }
        return $percentAnswered;
    }

    public function countProductsPurchasesAlum($idAlum)
    {
        $productsBought = 0;
        $user = User::with('purchases')->find($idAlum);
        $productsBought = $user->purchases->sum('stock');
        return $productsBought;
    }
}
