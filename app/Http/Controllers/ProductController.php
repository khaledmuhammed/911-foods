<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        if ( !isset($_COOKIE['lat']) ||!isset($_COOKIE['lng']) ) {
            return view('order.no_location');
        }
        $lat=$_COOKIE['lat'];
        $lon=$_COOKIE['lng'];
        

        return view('products.index')->with([
            "products" => Product::
            select('products.*')
                ->join('markets','markets.id','products.market_id')
            ->where('delivery_range','>=',DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(latitude))) "))

                ->withAvg('productReviews', 'rate')
                ->orderBy('product_reviews_avg_rate', 'desc')
                ->latest()
                ->paginate(6),
            "total" => Product::count(),
        ]);
    }

    /**
     * render top products based on product's rate
     */
    public function top_products(Request $request)
    {

        if (!isset($_COOKIE['lat']) || !isset($_COOKIE['lng'])) {
            return view('order.no_location');
        }
        $top_products = ProductReview::where('rate','>','4')->take(10)->get();

        return view('products.top_products')->with([
            "products" => Product::select('products.*')
                ->join('markets', 'markets.id', 'products.market_id')
                ->join('product_reviews', 'product_id', 'products.id')
                ->where('product_reviews.rate','>','4')

                ->withAvg('productReviews', 'rate')
                ->orderBy('product_reviews_avg_rate', 'desc')
                ->latest()
                ->paginate(6),
            "total" => count($top_products),
        ]);
    }
}
