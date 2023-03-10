<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCat()
    {
        $products = Product::with('category')->where('status', '!=', 0)->get();
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'nameProduct' => $product->name,
                'imageProduct' => asset('images/products/' . $product->image),
                'category' => $product->category->name,
                'status' => $product->status,
                'stock' => $product->stock,
                'puntos' => $product->points,
            ];
            // ...
        }

        return response()->json($data);
    }

    public function indexCatCollege()
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
            $data[] = [
                'nameProduct' => $product->name,
                'urlImage' => asset('images/products/' . $product->image),
                'categoria' => $product->category->name,
                'status' => $product->status,
                'puntos' => $product->points,
                'total_canjeados' => $totalPurchases,
                'total_puntos' => $totalPoints,
            ];
            // ...
        }
        return response()->json($data);
    }

    public function indexCatAlum()
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

                    $data[] = [
                        'id' => $product->id,
                        'nameProduct' => $product->name,
                        'categoria' => $product->category->name,
                        'puntos' => $product->points,
                        'stock' => $totalPurchases,
                        'total_puntos' => $totalPoints,
                        'image' => asset('images/products/' . $product->image),
                    ];

                }
            }
        }
        return response()->json($data);
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
    public function showProductoForCollege($id)
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
            $purchases = $product->purchases()
                ->whereHas('user.students.course.college', function ($query) use ($collegeId) {
                    $query->where('id', '=', $collegeId);
                })
                ->get();

            $uniquePurchases = $purchases->unique('user_id');

            $totalPurchases = $uniquePurchases->sum('stock');
            $totalPoints = $uniquePurchases->sum('points');

            $data = [
                'id' => $product->id,
                'nombre' => $product->name,
                'categoria' => $product->category->name,
                'puntos' => $product->points,
                'stock' => $product->stock,
                'fecha' => $purchases->first()->created_at->format('d-m-Y'),
                'hora' => $purchases->first()->created_at->format('H:i:s'),
                'status' => $product->status,
                'points_initial' => $totalPoints,
                'stock_ven' => $totalPurchases,
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
                'msg' => 'No se pudo obtener el producto',
            ]);
        }

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
