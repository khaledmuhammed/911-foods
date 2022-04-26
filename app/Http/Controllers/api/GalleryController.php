<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function marketGallerie(Market $market)
    {
        return response()->json(
            $market->galleries->map(function ($gallerie) {
                return [
                    "titel" => $gallerie->description,
                    "image" => $gallerie->getFirstMediaUrl('image')
                ];
            })
        );
    }
}
