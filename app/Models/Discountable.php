<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discountable extends Model
{
    use HasFactory;
    public $table = 'discountables';
    public $timestamps = false;

    public $fillable = [
        'coupon_id',
        'discountable_type',
        'discountable_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'coupon_id' => 'integer',
        'discountable_type' => 'string',
        'discountable_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'coupon_id' => 'required|exists:coupon,id',
        'discountable_type' => 'required',
        'discountable_id' => 'required'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function coupon()
    {
        return $this->belongsTo(\App\Models\Coupon::class, 'coupon_id', 'id');
    }

    public function discountable()
    {
        return $this->morphTo();
    }
}
