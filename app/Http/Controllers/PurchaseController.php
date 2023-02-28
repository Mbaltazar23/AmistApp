<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    //
    public function purchases()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Catalogo",
            'page_title' => 'Catalogo',
            'page_functions_js' => 'functions_catalogo.js',
        ];

        return view('purchases.index', compact('data'));
    }

    public function purchasesCat()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Catalogo",
            'page_title' => 'Catalogo',
            'page_functions_js' => 'functions_catalogoAlum.js',
        ];
        return view('purchases.catalogo', compact('data'));
    }

    public function purchasesAlum()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Mis Productos",
            'page_title' => 'Mis Productos',
            'page_functions_js' => 'functions_catAlumPurchase.js',
        ];
        return view('purchases.cat-alum', compact('data'));
    }

    /* funciones que cargaran los productos asociados a las compras */
    public function getPurchasesProducts()
    {
        $collegeId = Auth::user()->colleges->first()->college_id;

        $products = Product::has('purchases')
            ->whereHas('purchases.user.students.course.college', function ($query) use ($collegeId) {
                $query->where('id', '=', $collegeId);
            })
            ->with(['category', 'purchases.user.students.course.college'])
            ->get();

        $data = [];
        foreach ($products as $product) {
            $purchases = $product->purchases()->whereHas('user.students.course.college', function ($query) use ($collegeId) {
                $query->where('id', '=', $collegeId);
            })->get();

            $uniquePurchases = $purchases->unique('user_id');

            $totalPurchases = $uniquePurchases->sum('stock');
            $totalPoints = $uniquePurchases->sum('points');
            $btnView = '';
            $row = [
                'categoria' => $product->category->name,
                'puntos' => $product->points,
                'total_canjeados' => $totalPurchases,
                'total_puntos' => $totalPoints,
            ];
            if ($product->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $product->id . ')" title="Canjear Producto"><i class="far fa-eye"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $product->id . ')" title="Canjear Producto" disabled><i class="far fa-eye"></i></button>';
            }
            $row['nameP'] = "<img src='" . asset('images/products/' . $product->image) . "' alt='" . $product->name . "' class='img-circle img-size-32 mr-2'>" . $product->name;

            $row['options'] = '<div class="text-center">' . $btnView . '</div>';
            $data[] = $row;
            // ...
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getCatalogActive()
    {
        $products = Product::with('category')->where('status', '!=', 0)->get();
        $data = [];

        foreach ($products as $key => $product) {
            $btnCanjProd = '';
            $row = [
                'category' => $product->category->name,
                'stock' => $product->stock,
                'puntos' => $product->points,
            ];
            if ($product->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnCanjProd = '<button class="btn btn-dark btn-sm" onClick="fntCanjProduct(' . $product->id . ')" title="Canjear Producto"> <i class="fas fa-shopping-bag"></i></button>';

            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnCanjProd = '<button class="btn btn-secondary btn-sm" onClick="fntCanjProduct(' . $product->id . ')" title="Canjear Producto" disabled> <i class="fas fa-shopping-bag"></i></button>';

            }
            $row['nameP'] = "<img src='" . asset('images/products/' . $product->image) . "' alt='" . $product->name . "' class='img-circle img-size-32 mr-2'>" . $product->name;

            $row['options'] = '<div class="text-center">' . $btnCanjProd . '</div>';
            $data[] = $row;
            // ...
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getProductsPurchasesAlum()
    {
        $userId = Auth::user()->id;

        $collegeId = Auth::user()->colleges->first()->college_id;

        $products = Product::has('purchases')
            ->whereHas('purchases.user.students.course.college', function ($query) use ($collegeId) {
                $query->where('id', '=', $collegeId);
            })
            ->with(['category', 'purchases.user.students.course.college'])
            ->get();

        $data = [];
        foreach ($products as $product) {
            $purchases = $product->purchases()->whereHas('user.students.course.college', function ($query) use ($collegeId) {
                $query->where('id', '=', $collegeId);
            })->get();

            $uniquePurchases = $purchases->unique('user_id');

            foreach ($uniquePurchases as $purchase) {
                if ($purchase->user_id === $userId) {
                    $totalPurchases = $purchases->where('user_id', $purchase->user_id)->sum('stock');
                    $totalPoints = 0;

                    // Suma los puntos por usuario
                    $userPurchases = $purchases->where('user_id', $purchase->user_id);
                    foreach ($userPurchases as $userPurchase) {
                        $totalPoints += $userPurchase->points;
                    }

                    $btnView = '<button class="btn btn-danger btn-sm" onClick="fntDelCanj(' . $product->id . ')" title="Canjear Producto"><i class="far fa-trash-alt"></i></button>';

                    $row = [
                        'categoria' => $product->category->name,
                        'puntos' => $product->points,
                        'stock' => $totalPurchases,
                        'total_puntos' => $totalPoints,
                        'nameP' => "<img src='" . asset('images/products/' . $product->image) . "' alt='" . $product->name . "' class='img-circle img-size-32 mr-2'>" . $product->name,
                        'options' => '<div class="text-center">' . $btnView . '</div>',
                    ];

                    $data[] = $row;
                }
            }
        }
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);

    }

    /*Termino de funciones que cargen los productos */
    public function getPurchaseProduct($id)
    {

        $collegeId = Auth::user()->colleges->first()->college_id;

        $product = Product::has('purchases')
            ->where('id', '=', $id)
            ->whereHas('purchases.user.students.course.college', function ($query) use ($collegeId) {
                $query->where('id', '=', $collegeId);
            })
            ->with(['category', 'purchases.user.students.course.college'])
            ->first();

        if ($product) {
            $purchase = $product->purchases()->whereHas('user.students.course.college', function ($query) use ($collegeId) {
                $query->where('id', '=', $collegeId);
            })->first();

            $data = [
                'id' => $product->id,
                'nombre' => $product->name,
                'categoria' => $product->category->name,
                'puntos' => $product->points,
                'stock' => $product->stock,
                'fecha' => $purchase->created_at->format('d-m-Y'),
                'hora' => $purchase->created_at->format('H:i:s'),
                'status' => $product->status,
                'points_initial' => $purchase->points,
                'stock_ven' => $purchase->stock,
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
                'msg' => 'Producto obtenido correctamente',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No se pudo obtenerCanjear Producto',
            ]);
        }

    }

    public function setPurchase($id)
    {
        $idUser = Auth::user()->id;
        $product = Product::findOrFail($id);

        if ($product->points > Auth::user()->points) {
            return response()->json(['status' => false, 'msg' => 'No tienes suficientes puntos para comprar este producto']);
        }

        $purchase = Purchase::where('product_id', $id)->where('user_id', $idUser)->first();

        if ($purchase) {
            $purchase->stock += 1;
            $purchase->points += $product->points;
            $purchase->save();
        } else {
            $purchase = new Purchase([
                'user_id' => $idUser,
                'product_id' => $id,
                'stock' => 1,
                'points' => $product->points,
            ]);
            $purchase->save();
        }

        $product->stock -= 1;
        $product->save();

        $user = User::findOrFail($idUser);
        $user->points -= $product->points;
        $user->save();

        return response()->json(['status' => true, 'msg' => 'Compra realizada con éxito']);
    }

}
