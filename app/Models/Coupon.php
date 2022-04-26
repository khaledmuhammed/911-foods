<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public $table = 'coupons';

    public $fillable = [
        'code',
        'discount',
        'discount_type',
        'description',
        'expires_at',
        'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
        'discount' => 'double',
        'discount_type' => 'string',
        'description' => 'string',
        'expires_at' => 'datetime',
        'enabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|unique:coupons|max:50',
        'discount' => 'required|numeric|min:0',
        'discount_type' => 'required',
        'expires_at' => 'required|date|after_or_equal:tomorrow'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',

    ];

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function discountables()
    {
        return $this->hasMany(\App\Models\Discountable::class, 'coupon_id');
    }

    public function checkValidation()
    {
        if (Carbon::now() > $this->expires_at || !$this->enabled) {
            $data = ["valid" => false];
        } else {
            $categorys = array();
            $markets = array();
            $products = array();
            foreach ($this->discountables as $item) {
                switch ($item->discountable_type) {
                    case "App\Models\Product":
                        array_push($products, $item->discountable_id);
                        break;
                    case "App\Models\Market":
                        array_push($markets, $item->discountable_id);
                        break;
                    case "App\Models\Category":
                        array_push($categorys, $item->discountable_id);
                        break;
                    default:
                        break;
                }
            }
            $discountables = [
                "products" => $products,
                "markets" => $markets,
                "categorys" => $categorys
            ];
            $data = [
                "valid" => true,
                "discount_type" => $this->discount_type,
                "discount" => $this->discount,
                "discountables" => $discountables
            ];
        }
        return $data;
    }
}
