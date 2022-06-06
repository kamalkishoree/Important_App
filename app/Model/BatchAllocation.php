<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BatchAllocation extends Model
{
    
    protected $fillable = ['batch_no','geo_id','agent_id','batch_time', 'batch_type'];


    public function task(){
        return $this->hasMany('App\Model\BatchAllocationDetail', 'order_id', 'id')->orderBy('task_order');
    }

}
