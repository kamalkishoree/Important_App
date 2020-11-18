<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'order_id','task_type_id','location_id','pricing_rule_id','appointment_duration','dependent_task_id'
    ];


    public function order(){
        return $this->hasOne('App\Model\Order', 'id', 'order_id');
        
    }
}
