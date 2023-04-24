<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentCashCollectPop extends Model
{
    protected $table = 'agent_cash_collect_pop';

    public function agent(){
        return $this->hasOne(Agent::class,'id','agent_id');
    }
}
