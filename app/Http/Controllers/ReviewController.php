<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\MarketReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function create(Market $market)
    {
        return view('markets.review')->with([
            "market" => $market
        ]);
    }
    public function store(Market $market)
    {
        if (MarketReview::where('user_id', auth()->id())->where('market_id', $market->id)->first()) {
            return redirect()->back()->withErrors([Lang::get("reviewed")]);
        };
        request()->validate([
            'rate' => ['required', Rule::in(["0", "1", "2", "3", "4", "5"])],
            'review' => ['required'],
        ]);
        $review = MarketReview::create([
            'user_id' => auth()->id(),
            'market_id' => $market->id,
            'review' => request('review'),
            'rate' => request('rate')
        ]);
        if ($review) {
            return redirect("/markets/$market->id");
        }
        return redirect()->back();
    }
}
