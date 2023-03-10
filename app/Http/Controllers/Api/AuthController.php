<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Action;
use App\Models\Course;
use App\Models\College;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Purchase;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\PointAlumnAction;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
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

    public function cardPanel()
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

        $percentageActions = ($studentActionsCount / $totalActionsCount) * 100;

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

        $student_points_percentage = ($studentPointsActionsCount / $studentPointsCount) * 100;

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
        $teachersCount = Teacher::whereHas('course.college', function ($query) use ($collegeId) {
            $query->where('id', $collegeId);
        })
            ->whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->count();

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

}
