<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverGeo extends Model
{
    public function geo(){
        return $this->belongsTo('App\Model\Geo' , 'geo_id', 'id')->select('id', 'name', 'description', 'zoom_level', 'geo_array'); 
    }
}
