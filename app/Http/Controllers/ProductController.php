<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Productos",
            'page_title' => 'Productos',
            'page_functions_js' => 'functions_products.js',
        ];

        return view('products.index', compact('data'));
    }

    public function getProducts()
    {

        $products = Product::with('category')->get();
        $data = [];

        foreach ($products as $key => $product) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $row = [
                'category' => $product->category->name,
                'stock' => $product->stock,
                'points' => $product->points,
            ];
            if ($product->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $product->id . ')" title="Ver producto"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $product->id . ')" title="Editar producto"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $product->id . ')" title="Eliminar producto"><i class="far fa-trash-alt"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $product->id . ')" title="Ver producto" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $product->id . ')" title="Editar producto" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $product->id . ')" title="Activar producto"><i class="fas fa-toggle-on"></i></button>';
            }
            $row['nameP'] = "<img src='" . asset('images/products/' . $product->image) . "' alt='" . $product->name . "' class='img-circle img-size-32 mr-2'>" . $product->name;

            $row['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnDelete . '</div>';
            $data[] = $row;
            // ...
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);

    }

    public function setProduct(Request $request)
    {
        $id = $request->input('idProducto');
        $name = $request->input('txtNombre');
        $category = $request->input('listCategoria');
        $points = $request->input('txtPuntos');
        $stock = $request->input('txtStock');
        $foto = $request->file('image');
        $nombre_foto = "";
        if ($foto && $foto->isValid()) {
            $nombre_foto = $foto->getClientOriginalName();
            // resto del código
        }
        $imgPortada = 'product.png';

        if ($nombre_foto != '') {
            $imgPortada = 'prod_' . md5(date('d-m-Y H:i:s')) . '.jpg';
        }
        if ($id) {
            $product = Product::find($id);
            // actualizar producto
            if ($nombre_foto == '') {
                if ($request->input('foto_actual') != 'product.png' && $request->input('foto_remove') == 0) {
                    $imgPortada = $request->input('foto_actual');
                }
            }
            $product->name = $name;
            $product->category_id = $category;
            $product->points = $points;
            $product->stock = $stock;
            $product->image = $imgPortada;
            $product->save();
            if ($nombre_foto != '') {
                $foto->move(public_path('images/products'), $imgPortada);
            }
            if (($nombre_foto == '' && $request->input('foto_remove') == 1 && $request->input('foto_actual') != 'product.png') || ($nombre_foto != '' && $request->input('foto_actual') != 'product.png')) {
                $this->deleteFile($request->input('foto_actual'), "products");
            }
            return response()->json(['status' => true, 'msg' => 'Producto actualizado con éxito']);
        } else {
            // insertar categoría
            $product = new Product();
            $product->name = $name;
            $product->category_id = $category;
            $product->points = $points;
            $product->stock = $stock;
            $product->image = $imgPortada;
            $product->remember_token = Str::random(10);
            $product->save();
            if ($nombre_foto != '') {
                $foto->move(public_path('images/products'), $imgPortada);
            }
            return response()->json(['status' => true, 'msg' => 'Producto agregado con éxito']);
        }
    }

    public function getProduct($id)
    {
        $product = Product::with('category')->find($id);

        if ($product) {
            $data = [
                'id' => $product->id,
                'nombre' => $product->name,
                'category_id' => $product->category_id,
                'categoria' => $product->category->name,
                'puntos' => $product->points,
                'stock' => $product->stock,
                'fecha' => $product->created_at->format('d-m-Y'),
                'hora' => $product->created_at->format('H:i:s'),
                'status' => $product->status,
            ];

            if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                $data['image'] = $product->image;
                $data['url_image'] = asset('images/products/' . $product->image);
            } else {
                $data['image'] = null;
                $data['url_image'] = null;
            }

            return response()->json([
                'status' => true,
                'data' => $data,
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
        $producto = Product::find($id);

        if (!$producto) {
            return response()->json(['status' => false, 'msg' => 'El producto no existe']);
        }

        $status = $request->input('status');

        if ($status == 0) {
            $has_products = $producto->purchases()->exists();
            if ($has_products) {
                return response()->json(['status' => false, 'msg' => 'Este producto ya esta comprado']);
            }
        }

        $producto->status = $status;
        $producto->save();

        if ($status == 1) {
            $message = 'Producto Habilitado Exitosamente !!';
        } else {
            $message = 'Producto Inhabilitado Exitosamente !!';
        }

        return response()->json(['status' => true, 'msg' => $message]);
    }

    public function deleteFile(string $name, string $ruta): bool
    {
        $path = public_path('images/' . $ruta . '/' . $name);
        return Storage::disk('public')->delete($path);
    }

    public function getReport()
    {
        $products = Product::with('category')->get();
        $rowsProducts = [];
        foreach ($products as $product) {
            $rowsProducts[] = [
                'nombre' => $product->name,
                'imagen' => asset('images/products/' . $product->image),
                'categoria' => $product->category->name,
                'stock' => $product->stock,
                'puntos' => $product->points,
                'status' => $product->status,
            ];
        }
        return response()->json([
            'data' => $rowsProducts,
        ]);
    }

}
