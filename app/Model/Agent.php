<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Agent extends Authenticatable
{
	use Notifiable;

    protected $fillable = [
        'team_id', 'name', 'profile_picture', 'type', 'vehicle_type_id', 'make_model', 'plate_number', 'phone_number', 'color', 'is_activated', 'is_available'
    ];

    public function team(){
       return $this->belongsTo('App\Model\Team')->select("id", "name", "location_accuracy", "location_frequency"); 
    }

    public function vehicle_type(){
        return $this->belongsTo('App\Model\VehicleType'); 
    }

    public function geoFence(){
        return $this->hasMany('App\Model\DriverGeo' , 'driver_id', 'id')->select('driver_id', 'geo_id'); 
    }

    public function order(){
        return $this->hasMany('App\Model\Order','driver_id', 'id');
    }

}