<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\UserNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Notificaciones",
            'page_title' => 'Notificaciones',
            'page_functions_js' => 'functions_notificaciones.js',
        ];

        return view('notifications.index', compact('data'));
    }

    public function getNotifications()
    {
        $notifications = Notification::all();
        $data = [];
        foreach ($notifications as $key => $notification) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $row = [
                'nro' => $key + 1,
                'mensaje' => $notification->message,
                'tipo' => $notification->type != "Video/Message" ? "Pregunta" : "Video/Mensaje",
                'fecha' => $notification->created_at->format('d-m-Y'),
                'hora' => $notification->created_at->format('H:i:s'),
            ];
            if ($notification->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $notification->id . ')" title="Ver notificacion"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $notification->id . ')" title="Editar notificacion"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $notification->id . ')" title="Eliminar notificacion"><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $notification->id . ')" title="Ver notificacion" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary btn-sm" onClick="fntEditInfo(this,' . $notification->id . ')" title="Editar notificacion" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $notification->id . ')" title="Activar notificacion"><i class="fas fa-toggle-on"></i></button>';
            }
            $row['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnDelete . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setNotification(Request $request)
    {

        $idNotificacion = intval($request->input('idNotificacion'));
        $titleNotificacion = ucwords($request->input('title'));
        $listTypeNotificacion = $request->input('listTipoNotificacion');
        $Question = $request->has('Question') ? ucwords($request->input('Question')) : ucwords($request->input('Message'));
        $points = $request->has('pointsQuestion') ? $request->input('pointsQuestion') : $request->input('pointsMessage');
        $ArrAnswers = $request->has('Answers') ? $request->input('Answers') : '';
        $idQuestion = $request->has('idQuestion') ? intval($request->input('idQuestion')) : '';
        $idAnswers = $request->has('idAnswers') ? intval($request->input('idAnswers')) : '';
        $Message = $request->has('Message') ? ucwords($request->input('Message')) : '';
        $Response = $request->has('Response') ? ucfirst($request->input('Response')) : '';
        $Advice = $request->has('Advice') ? ucfirst($request->input('Advice')) : '';

        if (empty($listTypeNotificacion)) {
            return response()->json(['status' => false, 'msg' => 'Datos incorrectos.']);
        }

        if ($idNotificacion == 0) {
            $notification = new Notification;
            $notification->message = $titleNotificacion;
            $notification->type = $listTypeNotificacion;
            $notification->points = $points; // You need to add the points field to your Notification model
            $notification->remember_token = Str::random(10);

            $notification->save();
            $option = 1;
        } else {
            $notification = Notification::find($idNotificacion);
            if (!$notification) {
                return response()->json(['status' => false, 'msg' => 'La notificación no existe.']);
            }
            $notification->message = $titleNotificacion;
            $notification->type = $listTypeNotificacion;
            $notification->points = $points;
            $notification->save();
            $option = 2;
        }

        if ($option == 1) {
            $arrResponse = array('status' => true, 'msg' => 'Notificacion registrada Exitosamente !!');
            if ($listTypeNotificacion == env("TIPONOTQ")) {
                $question = new Question;
                $question->text_question = $Question;
                $question->notification_id = $notification->id;
                $question->save();

                foreach ($ArrAnswers['answers'] as $answer) {
                    $respuesta = ucfirst($answer["answer"]);
                    $consejo = ucfirst($answer["advice"]);
                    $answer = new Answer;
                    $answer->text_answer = $respuesta;
                    $answer->question_id = $question->id;
                    $answer->advice = $consejo;
                    $answer->save();
                }
            } else {
                $question = new Question;
                $question->text_question = $Message;
                $question->notification_id = $notification->id;
                $question->save();

                $answer = new Answer;
                $answer->text_answer = $Response;
                $answer->question_id = $question->id;
                $answer->advice = $Advice;
                $answer->save();
            }
        } else {
            $arrResponse = array('status' => true, 'msg' => 'Notificacion actualizada Exitosamente !!');
            if ($listTypeNotificacion == env("TIPONOTV")) {
                $question = Question::find($idQuestion);
                if (!$question) {
                    $arrResponse = array('status' => false, 'msg' => 'Pregunta no encontrada');
                    return response()->json($arrResponse);
                }
                $question->text_question = $Message;
                $question->save();

                $answer = Answer::find($idAnswers);
                if (!$answer) {
                    $arrResponse = array('status' => false, 'msg' => 'Respuesta no encontrada');
                    return response()->json($arrResponse);
                }
                $answer->text_answer = $Response;
                $answer->advice = $Advice;
                $answer->save();
            }
        }
        return response()->json($arrResponse);
    }

    public function getNotification($id)
    {
        $notificacion = Notification::with('questions.answers')->find($id);

        if (!$notificacion) {
            return null;
        }

        $response = [
            'id' => $notificacion->id,
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
            });

            $response['notifacion_message'] = $preguntas;
        } else {
            $pregunta = $notificacion->questions->first();

            if ($pregunta) {
                $preguntas = [
                    'id' => $pregunta->id,
                    'pregunta' => $pregunta->text_question,
                    'fecha' => $pregunta->created_at->format('d/m/Y'),
                    'hora' => $pregunta->created_at->format('H:i:s'),
                ];

                $respuestas = $pregunta->answers->map(function ($respuesta) {
                    return [
                        'id' => $respuesta->id,
                        'pregunta_id' => $respuesta->question_id,
                        'respuesta' => $respuesta->text_answer,
                        'consejo' => $respuesta->advice,
                    ];
                });

                $preguntas['AnswersQ'] = $respuestas;

                $response['notifacion_message'] = $preguntas;
            }
        }

        return response()->json([
            'status' => true, 'data' => $response]);
    }

    public function getQuestionsNotification($id)
    {
        $notificacion = Notification::with(['questions' => function ($query) {
            $query->orderBy('id', 'ASC');
        }])->find($id);

        $data = [];

        foreach ($notificacion->questions as $key => $pregunta) {
            $data[] = [
                'nro' => $key + 1,
                'pregunta' => $pregunta->text_question,
                'fecha' => $pregunta->created_at->format('d-m-Y'),
                'hora' => $pregunta->created_at->format('H:i:s'),
                'options' => '<div class="text-center"><button class="btn btn-primary btn-sm" onClick="fntEditQuestionInfo(this,' . $pregunta->id . ')" title="Editar Pregunta"><i class="fas fa-pencil-alt"></i></button> <button class="btn btn-danger btn-sm" onClick="fntDelQuestionInfo(' . $pregunta->id . ')" title="Eliminar Pregunta"><i class="far fa-trash-alt"></i></button></div>',
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setQuestion(Request $request)
    {
        $idNotificacion = $request->input('idNotificacion');
        $idQuestion = $request->input('idQuestion');
        $strQuestion = ucwords($request->input('Question'));
        $arrAnswers = $request->input('Answers');

        if (empty($strQuestion) || empty($arrAnswers)) {
            $arrResponse = array('status' => false, 'msg' => 'Datos incorrectos.');
            return response()->json($arrResponse);
        }

        // Insert or update question
        if ($idQuestion == 0) {
            $question = new Question();
            $question->notification_id = $idNotificacion;
            $question->text_question = $strQuestion;
            $question->save();
            $option = 1;
        } else {
            $question = Question::find($idQuestion);
            $question->notification_id = $idNotificacion;
            $question->text_question = $strQuestion;
            $question->save();
            $option = 2;
        }

        if ($question->id) {
            if ($option == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Pregunta registrada Exitosamente !!');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Pregunta actualizada Exitosamente !!');
            }

            // Insert or update answers
            foreach ($arrAnswers['answers'] as $answers) {
                $respuesta = ucfirst($answers['answer']);
                $consejo = ucfirst($answers['advice']);

                if (empty($answers['id'])) {
                    $answer = new Answer();
                    $answer->question_id = $question->id;
                    $answer->text_answer = $respuesta;
                    $answer->advice = $consejo;
                    $answer->save();
                } else {
                    $idRespuesta = intval($answers['id']);
                    $answer = Answer::find($idRespuesta);
                    $answer->text_answer = $respuesta;
                    $answer->advice = $consejo;
                    $answer->save();
                }
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
        }

        return response()->json($arrResponse);
    }

    public function getQuestion($id)
    {
        $question = Question::with('notification')
            ->where('id', $id)
            ->first();

        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }

        $answers = $question->answers;

        $result = [
            'idNot' => $question->notification->id,
            'mensaje' => $question->notification->message,
            'id' => $question->id,
            'pregunta' => $question->text_question,
            'fecha' => $question->created_at->format('d/m/Y'),
            'hora' => $question->created_at->format('H:i:s'),
            'answers' => $answers,
        ];

        return response()->json(['status' => true, 'data' => $result]);
    }

    public function setStatus($id, Request $request)
    {
        $status = $request->input('status');
        $notification = Notification::find($id);

        // Verificar si la notificación está asociada a un usuario
        $userNotification = UserNotification::where('notification_id', $notification->id)->first();
        if ($userNotification) {
            return response()->json(['status' => false, 'msg' => 'Esta Notificación ya está en uso']);
        }

        $notification->status = $status;
        $notification->save();

        if ($status == 1) {
            $message = 'Notificación Habilitada exitosamente !!';
        } else {
            $message = 'Notificación Inhabilitadad exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if ($question) {
            $notification = $question->notification;
            $userNotification = UserNotification::where('notification_id', $notification->id)->first();
            if (!$userNotification) {
                $question->answers()->delete(); // Elimina todas las respuestas asociadas a la pregunta
                $question->delete();
                return response()->json(['status' => true, 'msg' => 'Pregunta Eliminada Exitosamente !!']);
            } else {
                return response()->json(['status' => false, 'msg' => 'No se puede eliminar la pregunta al estar ya en uso por su Notificacion']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'No se puede eliminar la pregunta al estar ya en uso']);
        }
    }

    public function getReport()
    {
        $notifications = Notification::with(['questions.answers'])->get();
        $notifications_filtered = $notifications->filter(function ($notification) {
            return $notification->type == env('TIPONOTQ') || $notification->type == env('TIPONOTV');
        });
        $notifications_array = $notifications_filtered->map(function ($notification) {
            $contenido = '';
            $pregunta = '';
            $respuesta = '';
            $consejo = '';

            foreach ($notification->questions as $question) {
                foreach ($question->answers as $answer) {
                    $pregunta = $question->text_question;
                    $respuesta .= "- " . $answer->text_answer . "\n";
                    $consejo .= "- " . $answer->advice . "\n";
                }

                $contenido .= "- " . $pregunta . "\n" . "- Respuesta: " . $respuesta . "- Consejo: " . $consejo . "\n";
            }

            return [
                'NRO' => $notification->id,
                'NOMBRE' => $notification->message,
                'TIPO' => $notification->type,
                'CONTENIDO' => $contenido,
                'ESTADO' => $notification->status == 1 ? 'ACTIVO' : 'INACTIVO',
            ];
        })->toArray();

        return response()->json([
            'data' => $notifications_array,
        ]);
    }
}
