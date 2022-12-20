<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['category_id','product_id'];

    public function cat(){
	    return $this->hasOne('App\Model\CategoryTranslation', 'category_id', 'category_id')->select('id', 'name', 'category_id'); 
	}
}
