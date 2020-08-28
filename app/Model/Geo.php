<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    protected $fillable = ['name','description','zoom_level','geo_array','client_id'];

    public function agents(){
        return $this->belongsToMany('App\Model\Agent', 'driver_geos','geo_id','driver_id');
    }

    protected $appends = ['geo_coordinates'];

    //(33.54836127467958, -112.02277840462997),(33.44815709170538, -112.02689827767685),(33.46648880624111, -111.81953133431747),(33.57468140191938, -111.83395088998154)
    public function getGeoCoordinatesAttribute(){
      $temp = $this->geo_array;
      $temp = str_replace('(','[',$temp);
      $temp = str_replace(')',']',$temp);
      $temp = '['.$temp.']';
      $temp_array =  json_decode($temp,true);

      foreach($temp_array as $k=>$v){
        $data[] = [
            'lat' => $v[0],
            'lng' => $v[1]
        ];
      }
      return $data;
    }
}
