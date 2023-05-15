<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderVendorProduct extends Model
{
 
     protected  $fillable = ['order_id','quantity','vendor_id','product_id','task_id'];
    //
    public function product()
    {
        return $this->hasOne('App\Model\ProductVariant', 'id', 'product_id');
    }
}
