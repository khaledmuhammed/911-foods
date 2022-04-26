<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    public $table = 'faqs';

    public function category()
    {
        return $this->belongsTo(\App\Models\Faq_categorie::class, 'faq_category_id');
    }
}
