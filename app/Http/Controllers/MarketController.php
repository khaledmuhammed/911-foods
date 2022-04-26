<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function show(Market $market)
    {
        return view('markets.detail-market')->with(
            [
                'market' => $market,
                'market_cover' => $market->getFirstMediaUrl('image'),
                'market_fields' => $market->fields->first() ? $market->fields->first()->name : null,
                'market_rate' => $market->getRateAttribute(),
            ]
        );
    }
}
