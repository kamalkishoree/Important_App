<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentSlot extends Model
{
    // public function agentSlot()
    // {
    //     return $this->hasOne('App\Model\AgentSlot','id','slot_id'); 
    // }   
    public function SlotRoster()
    {
        return $this->hasMany('App\Model\AgentSlotRoster', 'slot_id', 'id');
    }

    public function SlotDay()
    {
        return $this->hasMany('App\Model\SlotDay', 'slot_id', 'id');
    }
}
