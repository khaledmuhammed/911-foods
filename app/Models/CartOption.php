<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOption extends Model
{
    use HasFactory;

    public $table = 'cart_options';
    public $timestamps = false;

    public $fillable = [
        'option_id',
        'cart_id',
    ];

    protected $casts = [
        'option_id' => 'integer',
        'cart_id' => 'integer',
    ];
}
