<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = ['title','body_html','meta_title','meta_keyword','meta_description','product_id','language_id'];
}
