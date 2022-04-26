<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

class FavoriteController extends Controller
{
    public function store($product_id, $user_id)
    {
        $user = User::findOrFail($user_id);
        $product = Product::findOrFail($product_id);
        foreach ($product->favorites as $favorite) {
            if ($favorite->user_id == $user->id) {
                if ($favorite->product_id == $product->id) {
                    $favorite->delete();
                    return response()->json([
                        "status" => "warn",
                        "message" => Lang::get("Product deleted form your wishlist")
                    ]);
                }
            };
        };
        $favorite = Favorite::create([
            'product_id' => $product->id,
            'user_id' => $user->id
        ]);
        if ($favorite) {
            return response()->json([
                "status" => "success",
                "message" => Lang::get("Product added form your wishlist")
            ]);
        }
        return response()->json([
            "status" => "error",
            "error" => Lang::get("Something went wrong")
        ]);
    }
}
