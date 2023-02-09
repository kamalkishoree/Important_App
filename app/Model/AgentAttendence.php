<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AgentAttendence extends Model
{

    public $appends = [
        'total'
    ];

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

    public function getTotalAttribute()
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $duration = $endTime->diffInMinutes($startTime);
        return date('H:i', mktime(0, $duration));
    }
}
