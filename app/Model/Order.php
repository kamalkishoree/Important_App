<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','scheduled_date_time','recipient_phone','Recipient_email','task_description','images_array','auto_alloction','driver_id','key_value_set','order_time','order_type','note'];

    public function customer(){
        return $this->hasOne('App\Model\Customer', 'id', 'customer_id')->select('id', 'name', 'email', 'phone_number');
        
    }
    public function location(){
        return $this->hasMany('App\Model\Location', 'customer_id', 'customer_id')
                    ->select('latitude', 'longitude', 'short_name', 'address', 'post_code', 'customer_id');
        
    }

    public function task(){
        return $this->hasMany('App\Model\Task', 'order_id', 'id');
        
    }

    public function agent(){
        return $this->hasOne('App\Model\Agent', 'id', 'driver_id')->select('id', 'team_id', 'name', 'type', 'phone_number');;
        
    }

    public function teamtags(){
        return $this->belongsToMany('App\Model\TaskTeamTag', 'task_team_tags','task_id','tag_id');
    }
    public function drivertags(){
        return $this->belongsToMany('App\Model\TaskDriverTag', 'task_driver_tags','task_id','tag_id');
    }

    public function customerdata()
    {
      return $this->hasOne('App\Model\Customer');
    }

    public function taskFirst()
    {
      return $this->task()->where('pricing_rule_id', 1);
    }



    
}
