<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\PointAlumnAction;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    //
    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Acciones",
            'page_title' => 'Acciones',
            'page_functions_js' => 'functions_acciones.js',
        ];

        return view('actions.index', compact('data'));
    }

    public function getActions()
    {
        $actions = Action::all();
        $data = [];

        foreach ($actions as $key => $action) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $row = [
                'nro' => $key + 1,
                'nombre' => $action->name,
                'puntos' => $action->points,
                'fecha' => $action->created_at->format('d-m-Y'),
                'hora' => $action->created_at->format('H:i:s'),
            ];
            if ($action->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $action->id . ')" title="Ver Accion"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $action->id . ')" title="Editar Accion"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $action->id . ')" title="Eliminar Accion"><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $action->id . ')" title="Ver Accion" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $action->id . ')" title="Editar Accion" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $action->id . ')" title="Activar Accion"><i class="fas fa-toggle-on"></i></button>';
            }
            $row['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnDelete . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setAction(Request $request)
    {
        $id = $request->input('idAccion');
        $name = ucwords($request->input('txtNombre'));
        $points = $request->input('txtPuntaje');
        $remember_token = Str::random(10);

        if ($id) {
            // actualizar accion
            $action = Action::find($id);
            $action->name = $name;
            $action->points = $points;
            $action->save();
            return response()->json(['status' => true, 'msg' => 'Accion actualizada Exitosamente !!', 'data' => $action]);
        } else {

            $count = Action::where('name', $name)->count();

            if ($count > 0) {
                return response()->json(['status' => false, 'msg' => 'Esta Accion ya existe']);
            } else {
                // insertar categoría
                $action = new Action();
                $action->name = $name;
                $action->points = $points;
                $action->remember_token = $remember_token;
                $action->save();
                return response()->json(['status' => true, 'msg' => 'Accion registrada Exitosamente !!', 'data' => $action]);
            }
        }
    }

    public function getAction($id)
    {
        $action = Action::find($id);

        if ($action) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $action->id,
                    'nombre' => $action->name,
                    'puntos' => $action->points,
                    'fecha' => $action->created_at->format('d-m-Y'),
                    'hora' => $action->created_at->format('H:i:s'),
                    'status' => $action->status,
                ],
                'msg' => 'Accion obtenida correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtener la categoría',
            ]);
        }

    }

    public function setStatus($id, Request $request)
    {
        $status = $request->input('status');
        $action = Action::find($id);

        // Si la accion está siendo desactivada (es decir, su status es 0), entonces verificamos si fueron ocupados por los alumnos.
        if ($status == 0) {
            $has_actions = PointAlumnAction::where('action_id', $action->id)->exists();
            if ($has_actions) {
                return response()->json(['status' => false, 'msg' => 'Esta Accion ya esta en uso']);
            }
        }

        $action->status = $status;
        $action->save();

        if ($status == 1) {
            $message = 'Accion Habilitada Exitosamente !!';
        } else {
            $message = 'Accion Inhabilitada Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function getReport()
    {
        $actions = Action::all();
        $formaterActions = [];
        foreach ($actions as $action) {
            $formaterActions[] = [
                'id' => $action->id,
                'nombre' => $action->name,
                'puntos' => $action->points,
                'fecha' => Carbon::parse($action->created_at)->format('d-m-Y'),
                'hora' => Carbon::parse($action->created_at)->format('H:i:s'),
                'status' => $action->status,
            ];
        }

        return response()->json([
            'data' => $formaterActions,
        ]);
    }

    public function getSelectActions()
    {
        $actions = Action::where('status', '!=', 0)->get();

        $html = '<option value="0">Seleccione una Accion</option>';
        foreach ($actions as $action) {
            $html .= '<option value="' . $action->id . '">' . $action->name . '</option>';
        }

        return $html;
    }
}
