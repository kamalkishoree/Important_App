<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'order_id','task_type_id','location_id','pricing_rule_id','appointment_duration','dependent_task_id','allocation_type','task_status'
    ];


    public function order(){
        return $this->hasOne('App\Model\Order', 'id', 'order_id');
        
    }

    public function location(){
        return $this->belongsTo('App\Model\Location', 'location_id', 'id');
        
    }

    public function teamtags(){
        return $this->belongsToMany('App\Model\TaskTeamTag', 'task_team_tags','task_id','tag_id');
    }
    public function drivertags(){
        return $this->belongsToMany('App\Model\TaskDriverTag', 'task_driver_tags','task_id','tag_id');
    }

    
}
