<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'phone_number','status'
    ];
    public function location(){
        $clientPreference =  getClientPreferenceDetail();
        if($clientPreference->show_limited_address ==1 ){
            return $this->hasMany('App\Model\Location', 'customer_id', 'id')->where('location_status',1)->orderBy('short_name','asc')->orderBy('address','asc')->take('5');
        }
        return $this->hasMany('App\Model\Location', 'customer_id', 'id')->where('location_status',1)->orderBy('short_name','asc')->orderBy('address','asc');
    }

    public function orders(){
        return $this->hasMany('App\Model\Order','customer_id', 'id');
    }
}