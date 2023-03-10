<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        return response()->json($notificationsToShow);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $intIdNot = intval($request->input('idNot'));
        $message = ucfirst($request->input("consejo"));
        $idUser = Auth::user()->id;
        // Buscar la notificación correspondiente
        $notification = Notification::find($intIdNot);

        // Verificar que la notificación existe
        if (!$notification) {
            return response()->json([
                'status' => false,
                'response' => 'La notificación no existe',
            ]);
        }

        // Obtener el usuario actual
        $user = User::find($idUser);

        // Verificar que el usuario existe
        if (!$user) {
            return response()->json([
                'status' => false,
                'response' => 'El usuario no está autenticado',
            ]);
        }

        // Sumar los puntos de la notificación al usuario
        $user->points += $notification->points;
        $user->save();

        // Crear el registro en UserNotification
        $userNotification = new UserNotification([
            'user_id' => $user->id,
            'notification_id' => $notification->id,
            'remember_token' => Str::random(10),
        ]);
        $userNotification->save();

        // Devolver la respuesta en formato JSON
        return response()->json([
            'status' => true,
            'msg' => $message,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $intId = decrypt($id);

        $notificacion = Notification::with('questions.answers')->find($intId);

        if (!$notificacion) {
            return null;
        }

        $response = [
            'id' => $notificacion->id,
            'idEncrypt' => encrypt($notificacion->id),
            'mensaje' => $notificacion->message,
            'tipo' => $notificacion->type,
            'type' => $notificacion->type != "Video/Message" ? "Pregunta" : "Video/Mensaje",
            'puntos' => $notificacion->points,
            'fecha' => $notificacion->created_at->format('d/m/Y'),
            'hora' => $notificacion->created_at->format('H:i:s'),
            'status' => $notificacion->status,
        ];

        if ($notificacion->type == env("TIPONOTV")) {
            $preguntas = $notificacion->questions->map(function ($pregunta) {
                return [
                    'id' => $pregunta->id,
                    'idRes' => $pregunta->answers->first()->id,
                    'pregunta' => $pregunta->text_question,
                    'respuesta' => $pregunta->answers->first()->text_answer,
                    'consejo' => $pregunta->answers->first()->advice,
                    'fecha' => $pregunta->created_at->format('d/m/Y'),
                    'hora' => $pregunta->created_at->format('H:i:s'),
                ];
            })->toArray();

            $response['notifacion_message'] = $preguntas;
        } else {
            $preguntas = $notificacion->questions->map(function ($pregunta) {
                $respuestas = $pregunta->answers->map(function ($respuesta) {
                    return [
                        'id' => $respuesta->id,
                        'pregunta_id' => $respuesta->question_id,
                        'respuesta' => $respuesta->text_answer,
                        'consejo' => $respuesta->advice,
                        'fecha' => $respuesta->created_at->format('d/m/Y'),
                        'hora' => $respuesta->created_at->format('H:i:s'),
                    ];
                });

                return [
                    'id' => $pregunta->id,
                    'pregunta' => $pregunta->text_question,
                    'fecha' => $pregunta->created_at->format('d/m/Y'),
                    'hora' => $pregunta->created_at->format('H:i:s'),
                    'respuestas' => $respuestas,
                ];
            });

            $response['notifacion_message'] = $preguntas;
        }

        return response()->json(['status' => true, 'data' => $response]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
