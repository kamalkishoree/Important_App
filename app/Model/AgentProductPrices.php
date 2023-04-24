<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentProductPrices extends Model
{
    protected $fillable = ['agent_id','product_id','product_variant_id','price','product_variant_sku'];

    public function agent()
    {
        return $this->hasOne('App\Model\Agent','id','agent_id'); 
    }  
}
