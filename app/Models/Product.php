<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'products';
    protected $appends = [
        'rate',
    ];

    //  get the maraket of the product
    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    public function options()
    {
        return $this->hasMany(\App\Models\Option::class, 'product_id');
    }

    // get all the reviews of the product
    public function productReviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class, 'product_id');
    }
    // get the rate of the product
    public function getRateAttribute()
    {
        return $this->productReviews()->select(DB::raw('round(AVG(product_reviews.rate),1) as rate'))->first('rate')->rate;
    }
    // get the price of the product
    public function getPrice(): float
    {
        return $this->discount_price > 0 ? $this->discount_price : $this->price;
    }
    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class, 'product_id');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset('/images/empty.jpg');
        }
    }
    public function format()
    {
        return  [
            'product' => $this,
            'cover' => $this->getFirstMediaUrl('image'),
            'market' => $this->market,
            'rate' => $this->rate,
            'category' => $this->category,
            'price' => getPrice($this->getPrice()),
            'discount' => $this->discount_price != 0 ? number_format(100 - ($this->discount_price * 100 / $this->price), 0) : null,
            'reviews' => count($this->productReviews)
        ];
    }
}
