<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    protected $fillable = ['category_id','product_id'];
}
