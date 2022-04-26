<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        return response()->json([
            "fields" => Field::withCount('markets')
                ->orderBy('markets_count', 'desc')
                ->take(6)
                ->get(),
        ]);
    }

    public function random()
    {
        return response()->json([
            "fields" => Field::inRandomOrder()->limit(4)->get(),
        ]);
    }
}
