<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Market;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Mail\ContactMail;   

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
    public function contact_us_store(Request $request)
    {
        $filename = null;
        if($request->file('image') != null){
            //Request File
            $file = $request->file('image');
            //Destination
            $destination = 'public/images/';
            //Define the name
            $name = "image";
            //Get file extension 
            $extension = $file->getClientOriginalExtension();
            //join the name you set with the extension
            $filename = $name . '.' . $extension;
            //after that move the file to the directory
            $file->move($destination, $filename);
        }
        $data = [
            'name' => $request->name,
            'market_name' => null,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'image' => $filename
        ];
        
        // dd( 'public/images/' . $data['image']);
        try {
            // dd('hi');
            if($request->image){
                Mail::to('info@911-foods.com')->send(new ContactMail($data));
                // Mail::to('info@911-foods.com')->send(new ContactMail($data))->attach($data['image']);
            }else{
                Mail::to('info@911-foods.com')->send(new ContactMail($data));
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        
        
        Session::flash('message', 'We received your message and we will reply to you as soon as possible, Thank you.');
        Session::flash('alert-class', 'alert-success');
        return Redirect::back();
    }

    public function contact_us_market_store(Request $request)
    {
        // dd($request);
        $data = [
            'name' => $request->name,
            'market_name' => $request->market_name,
            'branches_count' => $request->branches_count,
            'phone' => $request->phone,
            'message' => $request->message,
        ];
        try {
            // dd('hi');
            Mail::to('info@911-foods.com')->send(new ContactMail($data));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        
        
        Session::flash('message', 'We received your message and we will reply to you as soon as possible, Thank you.');
        Session::flash('alert-class', 'alert-success');
        return Redirect::back();
    }
}
