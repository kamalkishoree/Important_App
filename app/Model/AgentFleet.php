<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentFleet extends Model
{
    protected $fillable = ['agent_id','fleet_id'];


    public function fleetDetails()
    {
      return $this->hasOne('App\Model\Fleet');
    }
    
}
