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
        $etime = $this->end_time;
        if (empty($this->end_time)) {
            $etime = date("H:i:s", strtotime('23:59:00'));
        }
        $endTime = Carbon::parse($etime);
        $duration = $endTime->diffInMinutes($startTime);
        return date('H:i', mktime(0, $duration));
    }

    public function getDuration($hours)
    {
        $sum_minutes = 0;
        if(empty($hours)){
            return $this->total;
        }
        $array = [
            $this->total,
            $hours
        ];
        foreach ($array as $time) {
            $explodedTime = array_map('intval', explode(':', $time));
            $sum_minutes += $explodedTime[0] * 60 + $explodedTime[1];
        }
        $sumTime = floor($sum_minutes / 60) . ':' . floor($sum_minutes % 60);
        $decimals = explode(':', $sumTime);
            if (strlen($decimals[0]) == 1) {
                $sumTime = '0'.$sumTime;
            }
            if (strlen($decimals[1]) == 1) {
                $sumTime = $sumTime.'0';
            }
        
        return $sumTime;
    }
}
