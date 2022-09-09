<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentSlotRoster extends Model
{
    //
    protected $fillable = ['agent_id','start_time','end_time','schedule_date','booking_type','memo'];

    public function deleteVendorSlotDates($agent_id)
    {
    	return $this->where('agent_id',$agent_id)->delete();
    }

}
