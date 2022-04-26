<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderOption extends Model
{
    use HasFactory;
    public $table = 'product_order_options';
    public $timestamps = false;
    protected $guarded = [];
}
