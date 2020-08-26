<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'team_id', 'name', 'profile_picture', 'type', 'vehicle_type_id', 'make_model', 'plate_number', 'phone_number', 'color', 'is_activated', 'is_available'
    ];

    public function team(){
       return $this->belongsTo('App\Model\Team'); 
    }

    public function vehicle_type(){
        return $this->belongsTo('App\Model\VehicleType'); 
     }
}