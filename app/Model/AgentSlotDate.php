<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentSlotDate extends Model
{
    //
    protected $fillable = ['agent_id','start_time','end_time','specific_date','working_today'];

    public function deleteVendorSlotDates($agent_id)
    {
    	return $this->where('agent_id',$agent_id)->delete();
    }

}
