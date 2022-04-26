<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class User
 * @package App\Models
 * @version July 10, 2018, 11:44 am UTC
 *
 * @property string name
 * @property string email
 * @property string password
 * @property string api_token
 * @property string device_token
 */
class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable;

    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'device_token',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'device_token' => 'string',

    ];


    //  get media 
    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
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

    public function getHasMediaAttribute()
    {
        return $this->hasMedia('avatar') ? true : false;
    }

    public function favoriteProduct()
    {
        return $this->hasMany(\App\Models\Favorite::class, 'user_id')->latest();
    }

    public function cart()
    {
        return $this->hasMany(\App\Models\Cart::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'user_id')->latest();
    }


    public function customFieldValue()
    {
        return $this->hasMany(\App\Models\CustomFieldValue::class, 'customizable_id')
            ->where("customizable_type", "App\Models\User")
            ->latest();
    }
}
