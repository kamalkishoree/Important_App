<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $connection = 'royoorder';

    public function translation(){
        return $this->hasOne('App\Model\Order\CategoryTranslation')->where('category_translations.language_id', 1);
    }

    public function products()
    {
        return $this->hasMany('App\Model\Order\Product', 'category_id', 'id');
    }
}
