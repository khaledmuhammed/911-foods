<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Market extends Model implements HasMedia
{
    use HasFactory;
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'markets';
    protected $appends = [
        'rate',
    ];
    protected $hidden = [
        'media',
    ];
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_markets');
    }
    public function marketReviews()
    {
        return $this->hasMany(\App\Models\MarketReview::class, 'market_id');
    }
    public function getRateAttribute()
    {
        return $this->marketReviews()->select(DB::raw('round(AVG(market_reviews.rate),1) as rate'))->first('rate')->rate;
    }
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'market_id');
    }
    public function fields()
    {
        return $this->belongsToMany(\App\Models\Field::class, 'market_fields');
    }
    public function format_api()
    {
        return  $this->fields && $this->fields()->first() ? [
            $this,
            'cover' => $this->getFirstMediaUrl('image'),
            'rate' => $this->rate,
            'field' => $this->fields()->first()->name
        ] : [
            'market' => $this,
            'cover' => $this->getFirstMediaUrl('image'),
            'rate' => $this->rate,
        ];
    }
    public function format()
    {
        return  $this->fields && $this->fields()->first() ? [
            'market' => $this,
            'cover' => $this->getFirstMediaUrl('image'),
            'rate' => $this->rate,
            'field' => $this->fields()->first()->name
        ] : [
            'market' => $this,
            'cover' => $this->getFirstMediaUrl('image'),
            'rate' => $this->rate,
        ];
    }
    public function galleries()
    {
        return $this->hasMany(\App\Models\Gallery::class, 'market_id');
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
}
