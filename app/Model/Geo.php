<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    protected $fillable = ['name','description','geo_array'];

    public function agents(){
        return $this->belongsToMany('App\Model\Agent', 'driver_geos','geo_id','driver_id');
    }
}
