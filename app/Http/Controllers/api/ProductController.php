<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function topRated()
    {
        $lat=$_COOKIE['lat'];
        $lon=$_COOKIE['lng'];

        return response()->json([
            "products" => Product::select('products.*')
                ->join('markets','markets.id','products.market_id')
                ->where('delivery_range','>=',DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(latitude))) "))

                                ->withAvg('productReviews', 'rate')
                ->orderBy('product_reviews_avg_rate', 'desc')
                ->take(6)
                ->get()
                ->map
                ->format()
        ]);
    }
}
