<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $fillable = ['name','trans-slug','meta_title','meta_description','meta_keywords','category_id','language_id'];
}
