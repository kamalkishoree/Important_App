<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBidRideRequest extends Model
{
    protected $fillable = [
        'geo_id',
        'bid_id',
        'db_name',
        'client_code',
        'agent_tag',
        'tasks',
        'requested_price',
        'call_back_url',
        'expired_at',
        'customer_name',
        'customer_image',
        'minimum_request_price',
        'maximum_request_price',
        'expire_seconds'
    ];

    public function declinedbyAgent(){
        return $this->hasMany('App\Model\DeclineBidRequest','bid_id','id');
    }
}
