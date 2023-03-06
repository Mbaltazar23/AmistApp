<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->where('status', '!=', 0)->get();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
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
                'remember_token' => Str::random(10),
            ]);
            $purchase->save();
        }

        $product->stock -= 1;
        $product->save();

        $user = User::findOrFail($idUser);
        $user->points -= $product->points;
        $user->save();

        return response()->json(['status' => true, 'msg' => 'Producto canejado con éxito !!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $idUser = Auth::user()->id;
        $product = Product::findOrFail($id);

        $idProduct = $product->id;

        $purchase = Purchase::where('user_id', $idUser)
            ->where('product_id', $idProduct)
            ->firstOrFail();

        $product->stock += $purchase->stock;
        $product->save();

        $user = User::findOrFail($idUser);
        $user->points += $purchase->points;
        $user->save();

        $purchase->delete();
        return response()->json(['status' => true, 'msg' => 'Canjeo devuelta con éxito !!']);
    }
}
