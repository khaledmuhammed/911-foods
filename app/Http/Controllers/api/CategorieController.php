<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        return response()->json([
            "categorys" => Category::withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(6)
                ->get(),
        ]);
    }

    public function random()
    {
        return response()->json([
            "categorys" => Category::inRandomOrder()->limit(4)->get(),
        ]);
    }
}
