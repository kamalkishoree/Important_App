<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'phone_number','status'
    ];
    public function location(){
        return $this->hasMany('App\Model\Location', 'customer_id', 'id')->where('location_status',1);
        
    }

    public function orders(){
        return $this->hasMany('App\Model\Order','customer_id', 'id');
    }
}