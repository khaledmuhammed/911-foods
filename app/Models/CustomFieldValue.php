<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;

    public $table = 'custom_field_values';

    public $fillable = [
        'value',
        'view',
        'custom_field_id',
        'customizable_type',
        'customizable_id'
    ];

    protected $casts = [
        'value' => 'string',
        'view' => 'string',
        'custom_field_id' => 'integer',
        'customizable_type' => 'string',
        'customizable_id' => 'integer'
    ];

    public function customField()
    {
        return $this->belongsTo(\App\Models\CustomField::class, 'custom_field_id', 'id');
    }
}
