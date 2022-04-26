<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Field;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $response = [
            "categorys" => Category::withCount('products')
                ->withAvg('products', 'price')
                ->orderBy('products_count', 'desc')
                ->get(),
            "countMarket" => Market::count(),
        ];
        return view('welcome')->with($response);
    }
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|max:50',
        ]);
        if (str_starts_with($request->search, config('app.search_tags_key'))) {
            $search_value = str_replace(config('app.search_tags_key'), "", $request->search);
            $category = Category::where('name', $search_value)->first();
            $field = Field::where('name', $search_value)->first();
            $products = $category ? $category->products->take(12) : array();
            $markets = $field ?  $field->markets->take(12) : array();
            return view('search_result')->with([
                "category" => $category,
                "field" => $field,
                "search_value" => $search_value,
                "markets" => $markets,
                "products" => $products,
            ]);
        }
        $search_value = $request->search;
        $markets = Market::where('name', 'like', '%' . $search_value . '%')->take(12)->get();
        $products = Product::where('name', 'like', '%' . $search_value . '%')->take(12)->get();
        return view('search_result')->with([
            "field" => null,
            "category" => null,
            "search_value" => $search_value,
            "markets" => $markets,
            "products" => $products
        ]);
    }
    public function favoriteProducts($skip = 0)
    {
        $products = array();
        foreach (auth()->user()->favoriteProduct->skip($skip * 8)->take(8) as $fav) {
            $product = Product::find($fav->product_id);
            array_push($products, [
                'product' => $product,
                'cover' => $product->getFirstMediaUrl('image'),
                'market' => $product->market,
                'rate' => $product->getRateAttribute(),
                'category' => $product->category,
                'price' => $product->getPrice(),
                'discount' => $product->discount_price != 0 ? number_format(100 - ($product->discount_price * 100 / $product->price), 0) : null,
                'reviews' => count($product->productReviews)
            ]);
        }
        return response()->json(["products" => $products]);
    }
}
