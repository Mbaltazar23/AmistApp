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
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            return view('login', compact('data'));
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
        //$numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        //$nombredia = str_replace($dias_EN, $dias_ES, $dia);
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
                            "Alumnos" => array("pagina" => "alumnos-all"),
                            "Profesores" => array("pagina" => "profesores-all"),
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
                $idUser = Auth::user()->id;
                $user = User::with('colleges.college')->find($idUser);
                $collegeName = $user->colleges->first()->college->name;
                $navAdmin = array(
                    "Colegio Designado" => array(
                        "icon" => "fas fa-school",
                        "submodulos" => array(
                            $collegeName => array("pagina" => "dashboard"),
                        ),
                    ),
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
                            "Compañeros" => array("pagina" => "compañeros"),
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
                $navAdmin = array(
                    "Educacion" => array(
                        "icon" => "fas fa-regular fa-school",
                        "submodulos" => array(
                            "Alumnos" => array("pagina" => "alumnos-curso"),
                        ),
                    ),
                    "Catalogo" => array(
                        "icon" => "fas fa-solid fa-store",
                        "submodulos" => array(
                            "Productos" => array("pagina" => "catalogo-alumns"),
                        ),
                    ),
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
                        "color" => "bg-olive",
                        "value" => $percentageActions . "%",
                        "url" => "acciones",
                    ),
                    "student_points_percentage" => array(
                        "title" => "% de puntos entre Alumnos",
                        "icon" => "fas fa-users",
                        "color" => "bg-success",
                        "value" => $student_points_percentage . "%",
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
                $porcentage_productsPurchases = $this->counNotifications($collegeId);
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
                        "url" => "productos-cat",
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
                $pointsAvailable = $this->pointsAvailable($idUser);
                $countsStudents = $this->countAlumnsUser($idUser);
                $counNotifications = $this->countNotificationsForAlum($idUser);
                $productosPurchases = $this->countProductsPurchasesAlum($idUser);

                $cardsPanel = array(
                    "points Alumn" => array(
                        "title" => "Puntos disponibles",
                        "icon" => "fas fa fa-star-half-alt",
                        "color" => "bg-dark",
                        "value" => $pointsAvailable,
                        "url" => "compañeros",
                    ),
                    "companions of student" => array(
                        "title" => "Cantidad de compañeros",
                        "icon" => "fas fa fa-users",
                        "color" => "bg-orange",
                        "value" => $countsStudents,
                        "url" => "compañeros",
                    ),
                    "porcentage notifications for student" => array(
                        "title" => "Notificaciones contestadas",
                        "icon" => "fa fa-envelope-open-text",
                        "color" => "bg-blue",
                        "value" => $counNotifications,
                        "url" => "compañeros",
                    ),
                    "registered_colleges" => array(
                        "title" => "Cantidad de Productos adquiridos",
                        "icon" => "fas fa fa-cube",
                        "color" => "bg-purple",
                        "value" => $productosPurchases,
                        "url" => "productos-adquiridos",
                    ));
            } else {
                $idUser = Auth::user()->id;
                $collegeId = Auth::user()->colleges->first()->college_id;

                $pointsAvailable = $this->pointsAvailable($idUser);
                $alumnsForTeacher = $this->countsAlumnsForTeacher($idUser);
                $porcentajePointsAlumnsForTeacher = $this->porcentageActionsForAlumns($collegeId);
                $porcentajePoinstForTeacherAllAlumn = $this->porcentageActionsForTeacherAllAlumns($idUser);

                $cardsPanel = array(
                    "points Teacher" => array(
                        "title" => "Puntos disponibles",
                        "icon" => "fas fa fa-star-half-alt",
                        "color" => "bg-dark",
                        "value" => $pointsAvailable,
                        "url" => "alumnos-curso",
                    ),
                    "count alumns for student" => array(
                        "title" => "Cantidad de Alumnos a su cargo",
                        "icon" => "fas fa fa-users",
                        "color" => "bg-success",
                        "value" => $alumnsForTeacher,
                        "url" => "alumnos-curso",
                    ),
                    "porcentaje alumns actions for teacher" => array(
                        "title" => "% de Acciones entre sus Alumnos",
                        "icon" => "fas fa fa-check-circle",
                        "color" => "bg-blue",
                        "value" => $porcentajePointsAlumnsForTeacher . "%",
                        "url" => "alumnos-curso",
                    ),
                    "porcentaje alumns points for teacher" => array(
                        "title" => "% de Puntaje dado a sus Alumnos",
                        "icon" => "fas fa fa-star-half-alt",
                        "color" => "bg-warning",
                        "value" => $porcentajePoinstForTeacherAllAlumn . "%",
                        "url" => "alumnos-curso",
                    ));
            }
        }
        return $cardsPanel;
    }

    // Cards panel Dashboard Admin
    public function actionsUsedAlumns()
    {
        $percentageActions = 0;
        $studentActionsCount = PointAlumnAction::select('action_id')
            ->groupBy('action_id')
            ->get()
            ->count();

        $totalActionsCount = Action::where("status", "!=", 0)->count();

        $percentageActions = ($totalActionsCount > 0) ?? ($studentActionsCount / $totalActionsCount) * 100;

        return round($percentageActions, 2);
    }

    public function scoreThemStudents()
    {
        $student_points_percentage = 0;

        $studentPointsActionsCount = PointAlumnAction::whereHas('userRecept.roles', function ($query) {
            $query->where('role', env("ROLALU"));
        })->sum('points');

        $studentPointsCount = User::whereHas('roles', function ($query) {
            $query->where('role', env("ROLALU"));
        })->sum('points');

        $student_points_percentage = ($studentPointsCount > 0) ?? ($studentPointsActionsCount / $studentPointsCount) * 100;

        return round($student_points_percentage, 2);
    }

    public function countCollegeActives()
    {
        $collegesCount = College::where('status', 1)
            ->orWhereHas('courses', function ($query) {
                $query->where('status', 1);
            })
            ->count();
        return $collegesCount;
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
        $alumno_role_id = env("ROLALU");
        $alumnos = User::whereHas('roles', function ($query) use ($alumno_role_id) {
            $query->where('role', $alumno_role_id);
        })
            ->whereHas('colleges', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            })
            ->whereHas('students', function ($query) use ($collegeId) {
                $query->whereHas('course', function ($query) use ($collegeId) {
                    $query->where('college_id', $collegeId);
                });
            })
            ->where('status', "!=", 0)
            ->count();

        $total_alumnos = User::whereHas('roles', function ($query) use ($alumno_role_id) {
            $query->where('role', $alumno_role_id);
        })->count();

        if ($total_alumnos > 0) {
            $percentage = $alumnos / $total_alumnos * 100;
        } else {
            $percentage = 0;
        }

        return round($percentage, 2);
    }

    public function countsTeachersCourse($collegeId)
    {
        $teachersCount = Teacher::whereHas('courses', function ($query) use ($collegeId) {
            $query->where('college_id', $collegeId);
        })->whereHas('user', function ($query) {
            $query->where('status', 1);
        })->count();

        return $teachersCount;
    }

    public function counNotifications($collegeId)
    {
        $percentage = 0;
        $totalStock = Purchase::sum('stock');

        $collegeStock = Purchase::whereHas('user.students.course.college', function ($q) use ($collegeId) {
            $q->where('id', $collegeId);
        })->sum('stock');

        if ($totalStock > 0) {
            $percentage = round(($collegeStock / $totalStock) * 100, 2);
        }

        return $percentage;
    }

    public function porcentageStudentPerformActions($collegeId)
    {
        $percentActions = 0;
        $totalActions = PointAlumnAction::sum('points');

        $userActions = PointAlumnAction::whereHas('userRecept', function ($query) use ($collegeId) {
            $query->whereHas('colleges', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            })->whereHas('students.course', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            });
        })->sum('points');

        if ($totalActions > 0) {
            $percentActions = ($userActions / $totalActions) * 100;
        } else {
            $percentActions = 0;
        }
        return round($percentActions, 2);
    }

    //Cards Panel of Dashboard  : Alumn
    public function pointsAvailable($idAlum)
    {
        $user = User::find($idAlum);
        $pointsAvailable = $user->points;
        return $pointsAvailable;
    }

    public function countAlumnsUser($idAlum)
    {
        $user = User::with('colleges')->find($idAlum);
        $collegeId = $user->colleges->first()->college_id;
        $course = Course::where('college_id', $collegeId)
            ->whereHas('students', function ($query) use ($idAlum) {
                $query->where('user_id', $idAlum);
            })
            ->first();
        $numberOfStudents = Student::where('course_id', $course->id)
            ->where('user_id', '<>', $idAlum)
            ->whereHas('user', function ($query) {
                $query->where('status', '<>', 0);
            })
            ->count();
        return $numberOfStudents;
    }

    public function countNotificationsForAlum($idAlum)
    {
        $answeredNotifications = UserNotification::where('user_id', $idAlum)->count();

        return $answeredNotifications;
    }

    public function countProductsPurchasesAlum($idAlum)
    {
        $productsBought = 0;
        $user = User::with('purchases')->find($idAlum);
        $productsBought = $user->purchases->sum('stock');
        return $productsBought;
    }

    /*Cards Panel of Dashboard  : Profe */

    public function countsAlumnsForTeacher($idTeacher)
    {
        $alumnos = Student::whereHas('course.teachers', function ($query) use ($idTeacher) {
            $query->where('user_id', $idTeacher);
        })->count();
        return $alumnos;
    }

    public function porcentageActionsForAlumns($collegeId)
    {
        $actionsPercentage = 0;

        $course = Course::where('college_id', $collegeId)->get();
        $idCourse = $course->first()->id;

        $totalPointsActions = PointAlumnAction::whereHas('userRecept', function ($query) use ($collegeId) {
            $query->whereHas('colleges', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            })->whereHas('students.course', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            });
        })->sum('points');

        $alumnPointsActions = PointAlumnAction::whereHas('userRecept', function ($query) use ($collegeId, $idCourse) {
            $query->whereHas('colleges', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            })->whereHas('students', function ($query) use ($idCourse) {
                $query->where('course_id', $idCourse);
            });
        })->whereHas('action', function ($query) {
            $query->where('type', 'Alumno');
        })->sum('points');

        $actionsPercentage = ($totalPointsActions > 0) ? ($alumnPointsActions / $totalPointsActions) * 100 : 0;
        return round($actionsPercentage, 2);
    }

    public function porcentageActionsForTeacherAllAlumns($IdTeacher)
    {
        $pointsPercentage = 0;

        $totalPoints = PointAlumnAction::whereHas('action', function ($query) {
            $query->where('type', 'Profesor');
        })
            ->sum('points');

        $pointsGiven = PointAlumnAction::where('user_send_id', $IdTeacher)
            ->whereHas('action', function ($query) {
                $query->where('type', 'Profesor');
            })
            ->sum('points');

        $pointsPercentage = ($pointsGiven > 0) ? ($pointsGiven / $totalPoints) * 100 : 0;

        return round($pointsPercentage, 2);
    }

    public function getNotificationsToShow()
    {
        $now = Carbon::now();
        $idUser = Auth::user()->id;

        $notifications = Notification::where('status', 2)
            ->where('expiration_date', '>', $now)
            ->where('updated_at', '>=', $now->startOfDay())
            ->whereDoesntHave('usersNotifications', function ($query) use ($idUser) {
                $query->where('user_id', $idUser);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $notificationsToShow = [];

        foreach ($notifications as $notification) {
            // Verificar si el usuario ya ha respondido a esta notificación
            $userNotification = UserNotification::where('user_id', $idUser)
                ->where('notification_id', $notification->id)
                ->first();
            if (!$userNotification) {
                $notificationsToShow[] = [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'points' => $notification->points,
                    'time_left' => $notification->time_left,
                    'encryptedId' => encrypt($notification->id),
                ];
            }
        }

        return $notificationsToShow;

    }

}
