<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminColeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PointAlumnActionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    /* Modulo Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/profile', [DashboardController::class, 'show'])->name('dashboard.profile');
    Route::get('/dashboard/getProfile', [DashboardController::class, 'getProfile']);
    Route::post('/dashboard/putProfile', [DashboardController::class, 'setProfile']);
});

Route::middleware(['auth', 'checkrole:' . env("ROLADMIN")])->group(function () {
    /* Modulo Categorias */
    Route::get('/categorias', [CategoryController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::post('/categories/setCategoria', [CategoryController::class, 'setCategory']);
    Route::get('/categories/getCategory/{id}', [CategoryController::class, 'getCategory']);
    Route::post('/categories/status/{id}', [CategoryController::class, 'setStatus']);
    Route::post('/categories/select', [CategoryController::class, 'getSelectCategorys']);
    Route::post('/categories/report', [CategoryController::class, 'getReport']);

    /* Modulo Productos */
    Route::get('/productos', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::post('/products/setProduct', [ProductController::class, 'setProduct']);
    Route::get('/products/getProduct/{id}', [ProductController::class, 'getProduct']);
    Route::post('/products/status/{id}', [ProductController::class, 'setStatus']);
    Route::post('/products/report', [ProductController::class, 'getReport']);

    /* Modulo Administrador-Colegio */
    Route::get('/admin-colegio', [AdminColeController::class, 'index']);
    Route::get('/adminsColleges', [AdminColeController::class, 'getAdmins']);
    Route::post('/adminsColleges/setAdmin', [AdminColeController::class, 'setAdmin']);
    Route::get('/adminsColleges/getAdmin/{id}', [AdminColeController::class, 'getAdmin']);
    Route::post('/adminsColleges/setCollege', [AdminColeController::class, 'setCollegeAdmin']);
    Route::post('/adminsColleges/status/{id}', [AdminColeController::class, 'setStatus']);
    Route::post('/adminsColleges/delCollege/{id}', [AdminColeController::class, 'deleteCollegeAdmin']);
    Route::post('/adminsColleges/report', [AdminColeController::class, 'getReport']);

    /* Modulo Colegios */
    Route::get('/colegios', [CollegeController::class, 'index']);
    Route::get('/colleges', [CollegeController::class, 'getColleges']);
    Route::post('/colleges/setCollege', [CollegeController::class, 'setCollege']);
    Route::get('/colleges/getCollege/{id}', [CollegeController::class, 'getCollege']);
    Route::post('/colleges/status/{id}', [CollegeController::class, 'setStatus']);
    Route::post('/colleges/select', [CollegeController::class, 'getSelectColleges']);
    Route::post('/colleges/report', [CollegeController::class, 'getReport']);

    /* Modulo Notificaciones */
    Route::get('/notificaciones', [NotificationController::class, 'index']);
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/setNotification', [NotificationController::class, 'setNotification']);
    Route::get('/notifications/getNotification/{id}', [NotificationController::class, 'getNotification']);
    Route::get('/notifications/questions/{id}', [NotificationController::class, 'getQuestionsNotification']);
    Route::get('/notifications/question/{id}', [NotificationController::class, 'getQuestion']);
    Route::post('/notifications/setQuestion', [NotificationController::class, 'setQuestion']);
    Route::post('/notifications/status/{id}', [NotificationController::class, 'setStatus']);
    Route::post('/notifications/visible/{id}', [NotificationController::class, 'setStatusNot']);
    Route::post('/notifications/questionDel/{id}', [NotificationController::class, 'deleteQuestion']);
    Route::post('/notifications/report', [NotificationController::class, 'getReport']);

    /* Modulo Acciones */
    Route::get('/acciones', [ActionController::class, 'index']);
    Route::get('/actions', [ActionController::class, 'getActions']);
    Route::post('/actions/setAction', [ActionController::class, 'setAction']);
    Route::get('/actions/getAction/{id}', [ActionController::class, 'getAction']);
    Route::post('/actions/status/{id}', [ActionController::class, 'setStatus']);
    Route::post('/actions/select', [ActionController::class, 'getSelectActions']);
    Route::post('/actions/report', [ActionController::class, 'getReport']);
});

Route::middleware(['auth', 'checkrole:' . env("ROLADMINCOLE")])->group(function () {
    /* Modulo Cursos */
    Route::get('/cursos', [CourseController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'getCourses']);
    Route::post('/courses/setCourse', [CourseController::class, 'setCourse']);
    Route::get('/courses/getCourse/{id}', [CourseController::class, 'getCourse']);
    Route::post('/courses/status/{id}', [CourseController::class, 'setStatus']);
    Route::post('/courses/select/{rol}', [CourseController::class, 'getSelectCourses']);
    Route::post('/courses/report', [CourseController::class, 'getReport']);

    /* Modulo Alumnos */
    Route::get('/alumnos', [StudentController::class, 'index']);
    Route::get('/students', [StudentController::class, 'getStudents']);
    Route::post('/students/setStudent', [StudentController::class, 'setStudent']);
    Route::get('/students/getStudent/{id}', [StudentController::class, 'getStudent']);
    Route::post('/students/status/{id}', [StudentController::class, 'setStatus']);
    Route::post('/students/report', [StudentController::class, 'getReport']);

    /* Modulo Profesores */
    Route::get('/profesores', [TeacherController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'getTeachers']);
    Route::post('/teachers/setTeacher', [TeacherController::class, 'setTeacher']);
    Route::get('/teachers/getTeacher/{id}', [TeacherController::class, 'getTeacher']);
    Route::post('/teachers/status/{id}', [TeacherController::class, 'setStatus']);
    Route::post('/teachers/report', [TeacherController::class, 'getReport']);

    /*Modulo Catalogo*/
    Route::get('/productos-cat', [PurchaseController::class, 'purchases']);
    Route::get('/purchases', [PurchaseController::class, 'getPurchasesProducts']);
    Route::get('/purchases/getPurchase/{id}', [PurchaseController::class, 'getPurchaseProduct']);
    Route::post('/purchases/reportForCollege', [PurchaseController::class, 'getReportPurchases']);
});

Route::middleware(['auth', 'checkrole:' . env("ROLPROFE") . ',' . env("ROLALU")])->group(function () {
    /* Modulo Catalogo */
    Route::get('/catalogo', [PurchaseController::class, 'purchasesCat']);
    Route::get('/productos-adquiridos', [PurchaseController::class, 'purchasesAlum']);
    Route::get('/catalogo-alumns', [PurchaseController::class, 'purchasesTeacher']);
    Route::get('/purchases/alum', [PurchaseController::class, 'getProductsPurchasesAlum']);
    Route::get('/purchases/cat', [PurchaseController::class, 'getCatalogActive']);
    Route::get('/purchases/products-teacher', [PurchaseController::class, 'getProductsPurchasesTeacher']);
    Route::get('/purchases/getPurchaseTe/{id}', [PurchaseController::class, 'getPurchaseProductT']);
    Route::post('/purchases/setPurchase/{id}', [PurchaseController::class, 'setPurchase']);
    Route::post('/purchases/delPurchase/{id}', [PurchaseController::class, 'returnPurchaseProduct']);
    Route::post('/purchases/reportForTeacher', [PurchaseController::class, 'getReportPurchasesT']);

    /* Modulo Puntos - Alumno/Profesor*/
    Route::get('/compañeros', [PointAlumnActionController::class, 'indexActionAlumns']);
    Route::get('/alumnos-curso', [PointAlumnActionController::class, 'indexActionsTeacher']);
    Route::get('/companios', [PointAlumnActionController::class, 'getCompaniosAlum']);
    Route::get('/alumns-teacher', [PointAlumnActionController::class, 'getAlumnsTeacher']);
    Route::get('/companios/alum/{id}', [PointAlumnActionController::class, 'getStudentAlum']);
    Route::post('/companios/donate', [PointAlumnActionController::class, 'setPointsDonate']);

    /* Modulo Puntos/Notificaciones - Alumno */
    Route::get('/notificationQuest/getQuestion/{id}', [UserNotificationController::class, 'getNotificationShow']);
    Route::post('/notificationQuest/setQuestionNot',[UserNotificationController::class, 'setPointsNotification']);
});
