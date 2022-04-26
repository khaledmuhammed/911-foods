<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Category extends Model implements HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    use HasFactory;

    public $table = 'categories';

    public $fillable = [
        'name',
        'description'
    ];


    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }
    public function products_with_paginate()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id')->paginate(6);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function market()
    {
        return $this->belongsToMany(\App\Models\Market::class, 'products');
    }

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }

    public function getHasMediaAttribute()
    {
        return $this->hasMedia('icon') ? true : false;
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
