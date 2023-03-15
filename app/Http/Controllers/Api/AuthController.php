<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Course;
use App\Models\PointAlumnAction;
use App\Models\Student;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('dni', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }

        $user = Auth::user();

        $user = User::with('roles')->where('dni', $user->dni)->first();

        $college = College::whereHas('usersCollege', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        // Anida el colegio al usuario
        $user->college = $college ? $college : "";

        return response()->json([
            'token' => $token,
            'success' => true,
            'userData' => $user,
        ]);

    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        Auth::logout();

        return response()->json(['message' => 'Logout exitoso.']);
    }

    public function update(Request $request)
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
	
	
    public function navDashboard()
    {
        $navAdmin = "";
        foreach (Auth::user()->roles as $role) {
          if ($role->role == env("ROLALU")) {
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

    public function cardPanel()
    {
        $cardsPanel = [];
        foreach (Auth::user()->roles as $role) {
            if ($role->role == env("ROLALU")) {
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
                        "url" => "alumns",
                    ),
                    "count alumns for student" => array(
                        "title" => "Cantidad de Alumnos a su cargo",
                        "icon" => "fas fa fa-users",
                        "color" => "bg-success",
                        "value" => $alumnsForTeacher,
                        "url" => "alumns",
                    ),
                    "porcentaje alumns actions for teacher" => array(
                        "title" => "% de Acciones entre sus Alumnos",
                        "icon" => "fas fa fa-check-circle",
                        "color" => "bg-blue",
                        "value" => $porcentajePointsAlumnsForTeacher . "%",
                        "url" => "alumns",
                    ),
                    "porcentaje alumns points for teacher" => array(
                        "title" => "% de Puntaje dado a sus Alumnos",
                        "icon" => "fas fa fa-star-half-alt",
                        "color" => "bg-warning",
                        "value" => $porcentajePoinstForTeacherAllAlumn . "%",
                        "url" => "alumns",
                    ));
            }
        }
        return $cardsPanel;
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
