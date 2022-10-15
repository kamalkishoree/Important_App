<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'address', 'amenities', 'category_id'];

    public function amenity(){
        return $this->belongsToMany('App\Model\Amenities', 'warehouse_amenities')->withTimestamps();
    }
}
