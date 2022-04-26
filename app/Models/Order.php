<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $table = 'orders';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    public function product()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'product_orders');
    }
    public function market()
    {
        return $this->product->first()->market;
    }
    public function orderStatus()
    {
        return $this->belongsTo(\App\Models\OrderStatus::class, 'order_status_id', 'id');
    }
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id', 'id');
    }
    public function deliveryAddress()
    {
        return $this->belongsTo(\App\Models\DeliveryAddresse::class, 'delivery_address_id', 'id');
    }
    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class, 'payment_id', 'id');
    }
    public function productOrders()
    {
        return $this->hasMany(\App\Models\productOrder::class);
    }
}
