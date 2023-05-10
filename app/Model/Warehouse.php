<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'address', 'amenities', 'category_id', 'latitude', 'longitude','type'];

    public function amenity(){
        return $this->belongsToMany('App\Model\Amenities', 'warehouse_amenities')->withTimestamps();
    }

    public function category(){
        return $this->belongsToMany('App\Model\Category', 'warehouse_category')->withTimestamps();
    }

    public function manager(){
        return $this->belongsToMany('App\Model\Client', 'warehouse_manager_relation');
    }
}
