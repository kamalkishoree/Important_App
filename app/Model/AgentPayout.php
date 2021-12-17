<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentPayout extends Model
{
    public function agent(){
	    return $this->hasOne('App\Model\Agent' , 'id', 'agent_id'); 
	}

	// public function user(){
	//     return $this->hasOne('App\Model\User' , 'id', 'requested_by'); 
	// }

    public function payoutOption(){
        return $this->hasOne('App\Model\PayoutOption' , 'id', 'payout_option_id');
    }

    public function getStatusAttribute($value){
        if($value == '1'){
            return 'Paid';
        }elseif($value == '2'){
            return 'Failed';
        }else{
            return 'Pending';
        }
    }
}
