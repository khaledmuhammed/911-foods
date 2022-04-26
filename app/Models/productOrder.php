<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productOrder extends Model
{
    use HasFactory;
    public $table = 'product_orders';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }
    public function options()
    {
        return $this->belongsToMany(\App\Models\Option::class, 'product_order_options');
    }
}
