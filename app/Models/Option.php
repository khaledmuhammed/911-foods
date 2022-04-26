<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    public $table = 'options';

    public function group()
    {
        return $this->belongsTo(\App\Models\OptionGroup::class, 'option_group_id', 'id');
    }
}
