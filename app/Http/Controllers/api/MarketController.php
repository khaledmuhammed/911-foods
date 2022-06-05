<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function total()
    {
        return response()->json([
            "total" => Market::count(),
        ]);
    }
    public function index($skip)
    {
        $lat=$_COOKIE['lat'];
        $lon=$_COOKIE['lng'];
//        dd($currentUserInfo);

        return response()->json([
            "markets" =>Market::
            where('delivery_range','>=',DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(latitude))) "))

                ->take(9)
                ->skip(9 * $skip)
                ->with('fields')
                ->get()
                ->map
                ->format()
        ]);
    }
    public function topRated()
    {
        $lat=$_COOKIE['lat'];
        $lon=$_COOKIE['lng'];

        return response()->json([
            "markets" => Market::
            where('delivery_range','>=',DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(latitude))) "))
            ->withAvg('marketReviews', 'rate')
                ->orderBy('market_reviews_avg_rate', 'desc')
                ->take(4)
                ->with('fields')
                ->get()
                ->map
                ->format()
        ]);
    }
    public function search(Request $request)
    {
        return response()->json([
            "markets" => Market::where('name', 'like', '%' . $request->search . '%')
                ->take(10)
                ->get()
                ->map
                ->format()
        ]);
    }
    public function show(Market $market)
    {
        $products = array();
        $reviews = array();
        foreach ($market->products as $product) {
            $groups = array();
            foreach ($product->options as $option) {
                $gname = $option->group ? $option->group->name : "undefind groupe";
                $groups["$gname"] = [];
            }

            foreach ($product->options as $extra) {
                $gname = $extra->group ? $extra->group->name : "undefind groupe";
                array_push($groups["$gname"], $extra);
            }

            $likedBy = array();
            foreach ($product->favorites as $favorite) {
                array_push($likedBy, $favorite->user->id);
            }
            array_push($products, [
                "product" => $product,
                "price" => $product->getPrice(),
                "price_format" => getPrice($product->getPrice()),
                "cover" => $product->getFirstMediaUrl('image'),
                "rate" => $product->getRateAttribute() ? $product->getRateAttribute() : null,
                "category" => $product->category,
                "options_groups" => $groups,
                "isLikedBy" => $likedBy,
            ]);
        }
//        dd($products);
        foreach ($market->marketReviews as $review) {
            array_push($reviews, [
                "rate" => $review->rate,
                "review" => $review->review,
                "user" => $review->user,
                "user_avatar" => $review->user->getFirstMediaUrl('avatar'),
                "published" => $review->created_at->diffForHumans(),
            ]);
        }
        
        return response()->json([
            "market" => $market,
            "market_rate" => $market->rate,
            "reviews" => $reviews,
            "products" => $products,
            "pictures" => $market->getMedia('picture'),
        ]);
    }
    public function filter(Request $request)
    {

        $markets = array();
        if ($request->price != null) {
            foreach (Market::all() as $market) {
                foreach ($market->products as $product) {
                    if ($product->getPrice() >= $request->price['min'] && $product->getPrice() <= $request->price['max']) {
                        if (!in_array($market->id, $markets)) {
                            array_push($markets, $market->id);
                        }
                    }
                }
            }
        };
        if ($request->rate != null) {
            foreach (Market::withAvg('marketReviews', 'rate')->get() as $market) {
                if ($market->market_reviews_avg_rate >= $request->rate) {
                    if (!in_array($market->id, $markets)) {
                        array_push($markets, $market->id);
                    }
                }
            }
        }
        if ($request->fields) {
            if (count($request->fields) > 0) {
                foreach ($request->fields as $field_id) {
                    foreach (Market::all() as $market) {
                        foreach ($market->fields as $fields) {
                            if ($fields->id == $field_id) {
                                if (!in_array($market->id, $markets)) {
                                    array_push($markets, $market->id);
                                }
                            }
                        }
                    }
                }
            }
        }
        $response = array();
        foreach ($markets as $market_id) {
            array_push($response, Market::find($market_id)->format());
        }
        return response()->json([
            "markets" => $response,
        ]);
    }
}
