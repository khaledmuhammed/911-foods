<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Category $category)
    {
        return view('categoryProduct')->with([
            "category" => $category,
            "products" => $category->products_with_paginate(),
        ]);
    }
}
