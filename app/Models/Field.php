<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    public $table = 'fields';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function markets()
    {
        return $this->belongsToMany(\App\Models\Market::class, 'market_fields');
    }
    public function market_with_paginate()
    {
        return $this->belongsToMany(\App\Models\Market::class, 'market_fields')->paginate(12);
    }
}
