<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    public function index(Field $field)
    {
        return view('fieldsMarket')->with([
            "field" => $field,
            "markets" => $field->market_with_paginate(),
        ]);
    }
}
