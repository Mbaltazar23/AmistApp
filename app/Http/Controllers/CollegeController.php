<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    //
    public function index()
    {

        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Colegios",
            'page_title' => 'Colegios',
            'page_functions_js' => 'functions_colegios.js',
        ];

        return view('colleges.index', compact('data'));
    }

    public function getColleges()
    {
        $colleges = College::all();
        $data = [];
        foreach ($colleges as $key => $college) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $row = [
                'rut' => $college->dni,
                'nombre' => $college->name,
                'telefono' => $college->phone,
            ];
            if ($college->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $college->id . ')" title="Ver Colegio"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $college->id . ')" title="Editar Colegio"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $college->id . ')" title="Eliminar Colegio"><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $college->id . ')" title="Ver Colegio" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $college->id . ')" title="Editar Colegio" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $college->id . ')" title="Activar Colegio"><i class="fas fa-toggle-on"></i></button>';
            }
            $row['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnDelete . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setCollege(Request $request)
    {
        $id = $request->input('idColegio');
        $dni = $request->input('txtRut');
        $name = ucwords($request->input('txtNombre'));
        $phone = $request->input('txtTelefono');
        $stockAlums = $request->input("txtStockAlumns");
        $address = ucfirst($request->input('txtDireccion'));
        if ($id) {
            // actualizar colegio
            $college = College::find($id);
            $college->dni = $dni;
            $college->name = $name;
            $college->phone = $phone;
            $college->stock_alumns = $stockAlums;
            if (!$address) {
                $college->address = '';
            } else {
                $college->address = $address;
            }
            $college->save();
            return response()->json(['status' => true, 'msg' => 'Colegio actualizado Exitosamente !!', 'data' => $college]);
        } else {
            // insertar colegio
            $college = new College();
            $college->dni = $dni;
            $college->name = $name;
            $college->phone = $phone;
            $college->stock_alumns = $stockAlums;
            $college->remember_token = Str::random(10);
            if (!$address) {
                $college->address = '';
            } else {
                $college->address = $address;
            }
            $college->save();
            return response()->json(['status' => true, 'msg' => 'Colegio agregado Exitosamente !!', 'data' => $college]);
        }
    }

    public function getCollege($id)
    {
        $college = College::find($id);

        if ($college) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $college->id,
                    'rut' => $college->dni,
                    'nombre' => $college->name,
                    'telefono' => $college->phone,
                    'direccion' => $college->address,
                    'stock_alumnos' => $college->stock_alumns,
                    'fecha' => $college->created_at->format('d-m-Y'),
                    'hora' => $college->created_at->format('H:i:s'),
                    'status' => $college->status,
                ],
                'msg' => 'Colegio obtenido correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtener el colegio',
            ]);
        }
    }

    public function setStatus($id, Request $request)
    {
        $status = $request->input('status');
        $college = College::find($id);

        if (!$college) {
            return response()->json(['status' => false, 'msg' => 'El Colegio no existe']);
        }

        $college->status = $status;
        $college->save();

        if ($status == 1) {
            $message = 'Colegio Habilitado Exitosamente !!';
        } else {
            $message = 'Colegio Inhabilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);

    }

    public function getReport()
    {
        $colleges = College::all();
        return response()->json([
            'data' => $colleges,
        ]);
    }

    public function getSelectColleges()
    {
        $colleges = College::where('status', '!=', 0)->get();

        $html = '<option value="0">Seleccione un Colegio</option>';
        foreach ($colleges as $college) {
            $html .= '<option value="' . $college->id . '">' . ucwords($college->name) . '</option>';
        }

        return $html;
    }

}
