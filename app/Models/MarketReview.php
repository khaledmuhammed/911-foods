<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketReview extends Model
{
    use HasFactory;

    public $table = 'market_reviews';

    protected $fillable = [
        'user_id',
        'market_id',
        'rate',
        'review',
    ];

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
