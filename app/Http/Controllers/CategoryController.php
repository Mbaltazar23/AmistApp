<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {

        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Categorias",
            'page_title' => 'Categorias',
            'page_functions_js' => 'functions_categorias.js',
        ];

        return view('categories.index', compact('data'));
    }

    public function getCategories()
    {

        $categories = Category::all();
        $data = [];
        foreach ($categories as $key => $category) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $row = [
                'nro' => $key + 1,
                'name' => $category->name,
                'fecha' => $category->created_at->format('d-m-Y'),
                'hora' => $category->created_at->format('H:i:s'),
            ];
            if ($category->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $category->id . ')" title="Ver categoría"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $category->id . ')" title="Editar categoría"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $category->id . ')" title="Eliminar categoría"><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . ($key + 1) . ',' . $category->id . ')" title="Ver categoría" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $category->id . ')" title="Editar categoría" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $category->id . ')" title="Activar categoría"><i class="fas fa-toggle-on"></i></button>';
            }
            $row['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnDelete . '</div>';
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function setCategory(Request $request)
    {
        $id = $request->input('id');
        $name = ucwords($request->input('name'));
        $remember_token = Str::random(10);
        if ($id) {
            // actualizar categoría
            $category = Category::find($id);
            $category->name = $name;
            $category->save();
            return response()->json(['status' => true, 'msg' => 'Categoría actualizada con éxito', 'data' => $category]);
        } else {

            $count = Category::where('name', $name)->count();

            if ($count > 0) {
                return response()->json(['status' => false, 'msg' => 'Esta Categoría ya existe']);
            } else {
                // insertar categoría
                $category = new Category();
                $category->name = $name;
                $category->remember_token = $remember_token;
                $category->save();
                return response()->json(['status' => true, 'msg' => 'Categoría agregada con éxito', 'data' => $category]);
            }
        }

    }

    public function getCategory($id)
    {
        $categoria = Category::find($id);

        if ($categoria) {
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $categoria->id,
                    'nombre' => $categoria->name,
                    'fecha' => $categoria->created_at->format('d-m-Y'),
                    'hora' => $categoria->created_at->format('H:i:s'),
                    'status' => $categoria->status,
                ],
                'msg' => 'Categoría obtenida correctamente',
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
        $category = Category::find($id);

        // Si la categoría está siendo desactivada (es decir, su status es 0), entonces verificamos si hay productos asociados a ella antes de desactivarla.
        if ($status == 0) {
            $has_products = Product::where('category_id', $category->id)->exists();
            if ($has_products) {
                return response()->json(['status' => false, 'msg' => 'Esta Categoria ya esta en uso']);
            }
        }

        $category->status = $status;
        $category->save();

        if ($status == 1) {
            $message = 'Categoria Habilitada Exitosamente !!';
        } else {
            $message = 'Categoria Inhabilitada Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function getReport()
    {
        $categories = Category::all();
        $formattedCategories = [];
        foreach ($categories as $category) {
            $formattedCategories[] = [
                'id' => $category->id,
                'nombre' => $category->name,
                'fecha' => Carbon::parse($category->created_at)->format('d-m-Y'),
                'hora' => Carbon::parse($category->created_at)->format('H:i:s'),
                'status' => $category->status,
            ];
        }

        return response()->json([
            'data' => $formattedCategories,
        ]);
    }

    public function getSelectCategorys()
    {
        $categorias = Category::where('status', '!=', 0)->get();

        $html = '<option value="0">Seleccione una Categoria</option>';
        foreach ($categorias as $categoria) {
            $html .= '<option value="' . $categoria->id . '">' . $categoria->name . '</option>';
        }

        return $html;
    }
}
