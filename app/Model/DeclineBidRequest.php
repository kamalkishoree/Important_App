<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeclineBidRequest extends Model
{
    protected $fillable = [
        'bid_id',
        'agent_id',
        'status'
    ];

    public function declinedbyAgent(){
        return $this->belongsTo('App\Model\UserBidRideRequest','bid_id','id');
    }
}
