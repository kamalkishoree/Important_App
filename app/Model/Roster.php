<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = ['order_id','driver_id','notification_time','type'];

    public function agent(){
        return $this->hasOne('App\Model\Agent', 'id', 'driver_id')->select('id', 'team_id', 'name', 'type', 'phone_number','device_type','device_token');
        
    }
}
