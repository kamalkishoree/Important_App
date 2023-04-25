<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;
  
  // protected $connection = 'royoorder';
  
  public function variant(){
    return $this->hasMany('App\Model\Order\ProductVariant')->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'barcode', 'cost_price', 'currency_id', 'tax_category_id','container_charges','markup_price')->where('status', 1);
  }

  public function translation(){
    return $this->hasOne('App\Model\Order\ProductTranslation')->where('language_id', 1);
  }

}
