<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgentAttendence extends Model
{

    protected $table = 'agent_attendence';

    protected $fillable = [
        'agent_id',
        'start_time',
        'end_time',
        'start_date',
        'end_date'
    ];

    public function agent()
    {
        return $this->hasOne('App\Model\Agent', 'id', 'agent_id');
    }
}
