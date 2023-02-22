<?php

namespace App\Http\Controllers;

use App\Models\Product;

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

    public function purchasesAlum()
    {
        $data = [
            'page_tag' => env("NOMBRE_WEB") . " - Catalogo",
            'page_title' => 'Catalogo',
            'page_functions_js' => 'functions_catalogoAlum.js',
        ];
        return view('purchases.catalogo', compact('data'));
    }

    public function getPurchasesProducts()
    {
        $products = Product::has('purchases')->with('category')->get();

        $data = [];

        foreach ($products as $product) {
            $purchases = $product->purchases;
            $totalPurchases = $purchases->count();
            $totalPoints = $purchases->sum('product.points');
            $btnView = '';
            $row = [
                'categoria' => $product->category->name,
                'puntos' => $product->points,
                'total_canjeados' => $totalPurchases,
                'total_puntos' => $totalPoints,
            ];
            if ($product->status == 1) {
                $row['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $product->id . ')" title="Ver producto"><i class="far fa-eye"></i></button>';
            } else {
                $row['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $product->id . ')" title="Ver producto" disabled><i class="far fa-eye"></i></button>';
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
}
