<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;
    public $table = 'custom_fields';

    public $fillable = [
        'name',
        'type',
        'values',
        'disabled',
        'required',
        'in_table',
        'bootstrap_column',
        'order',
        'custom_field_model'
    ];

    protected $casts = [
        'name' => 'string',
        'type' => 'string',
        'values' => 'array',
        'disabled' => 'boolean',
        'required' => 'boolean',
        'in_table' => 'boolean',
        'custom_field_model' => 'string'
    ];

    public function isPhone()
    {
        return $this->name == "phone";
    }
    public function isAddress()
    {
        return $this->name == "address";
    }
    public function isBio()
    {
        return $this->name == "bio";
    }
}
