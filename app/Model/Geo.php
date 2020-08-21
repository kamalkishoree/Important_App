<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    protected $fillable = ['name','description','zoom_level','geo_array','client_id'];

    public function agents(){
        return $this->belongsToMany('App\Model\Agent', 'driver_geos','geo_id','driver_id');
    }
}
