<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingTypes extends Model
{
    use SoftDeletes;

    public function orderRating(){
	    return $this->hasOne('App\Model\DriverRating','rating_type_id','id'); 
	}
}
