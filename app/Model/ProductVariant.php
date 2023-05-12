<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['sku','product_id','title','quantity','price','position','compare_at_price','cost_price','barcode','currency_id','tax_category_id','inventory_policy','fulfillment_service','inventory_management','status', 'container_charges','markup_price'];


    public function product(){
        return $this->hasOne('App\Model\Product','id','product_id');
    }
    public function agentPrice(){
	    return $this->hasOne('App\Model\AgentProductPrices'); 
	}
}
