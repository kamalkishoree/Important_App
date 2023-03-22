<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InventoryVendor extends Model
{

    protected $table = "inventory_vendors";
    
    public function warehouseProducts(){
        return $this->hasMany('App\Model\Product', 'vendor_id','id')->select('id', 'title', 'sku');
    }
}
