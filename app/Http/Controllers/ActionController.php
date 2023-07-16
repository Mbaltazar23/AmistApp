<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $type = $request->input('txtVisible');
        $points = $request->input('txtPuntaje');
        $remember_token = Str::random(10);

        if ($id) {
            // actualizar accion
            $action = Action::find($id);
            $action->name = $name;
            $action->points = $points;
            $action->type = $type;
            $action->save();
            return response()->json(['status' => true, 'msg' => 'Accion actualizada Exitosamente !!', 'data' => $action]);
        } else {

            $count = Action::where('name', $name)
                ->where('type', $type)
                ->count();

            if ($count > 0) {
                return response()->json(['status' => false, 'msg' => 'Esta Accion ya existe']);
            } else {
                // insertar categoría
                $action = new Action();
                $action->name = $name;
                $action->type = $type;
                $action->points = $points;
                $action->remember_token = $remember_token;
                $action->save();
                return response()->json(['status' => true, 'msg' => 'Accion registrada Exitosamente !!',
                    'data' => $action]);
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
                    'tipo' => $action->type,
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

        // Si la accion está siendo desactivada (es decir, su status es 0), entonces verificamos si fueron ocupados por los alumnos
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
        $actions = Action::with('collegeUsers')->get();
        $formaterActions = [];
        foreach ($actions as $action) {
            $collegeNames = $action->collegeUsers->pluck('college.name')->implode(', '); // Obtener los nombres de los colegios separados por coma
            $collegeCounts = $action->collegeUsers->countBy('college.name')->toArray(); // Obtener la cantidad de veces que se utilizó la acción por cada colegio
            $formaterActions[] = [
                'id' => $action->id,
                'nombre' => $action->name,
                'puntos' => $action->points,
                'tipo' => "Para " . $action->type,
                'fecha' => Carbon::parse($action->created_at)->format('d-m-Y'),
                'hora' => Carbon::parse($action->created_at)->format('H:i:s'),
                'status' => $action->status,
                'colegios' => $collegeNames, // Agregar la información de los colegios
                'cantidad_colegios' => $collegeCounts, // Agregar la cantidad de veces que se utilizó la acción por cada colegio
            ];
        }

        return response()->json([
            'data' => $formaterActions,
        ]);
    }

    public function getSelectActions()
    {
        $role = Auth::user()->roles->first()->role;

        $actions = Action::where('status', '!=', 0)->where('type', $role)
            ->get();

        $html = '<option value="0">Seleccione una Accion</option>';
        foreach ($actions as $action) {
            $html .= '<option value="' . $action->id . '" data-points="' . $action->points . '">' . $action->name . '</option>';
        }

        return $html;
    }
}
