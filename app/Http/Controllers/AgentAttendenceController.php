<?php
namespace App\Http\Controllers;

use DB;
use Exception;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use App\Model\ {
    Agent
};
use App\Model\AgentAttendence;

class AgentAttendenceController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function returnJson(Request $request, $domain = '', $id = '')
    {
        $Agent = Agent::findOrFail($id);
        $date = $day = array();

        if ($request->has('start')) {
            $start = explode('T', $request->start);
            $end = explode('T', $request->end);

            $startDate = date('Y-m-d', strtotime($start[0]));
            $endDate = date('Y-m-d', strtotime($end[0]));

            $datetime1 = new \DateTime($startDate);
            $datetime2 = new \DateTime($endDate);

            $interval = $datetime2->diff($datetime1);
            $days = $interval->format('%a');

            $date[] = $startDate;
            $day[] = 1;

            for ($i = 1; $i < $days; $i ++) {
                $date[] = date('Y-m-d', strtotime('+' . $i . ' day', strtotime($startDate)));
                $day[] = $i + 1;
            }
        } else {
            $startDate = '';
            $endDate = '';
        }
        $AgentAttendences = AgentAttendence::where('agent_id', $Agent->id)->whereBetween('start_date', [
            $startDate,
            $endDate
        ])
            ->orderBy('start_date', 'asc')
            ->get();

        $showData = array();
        $count = 0;
        $total = '';
        $totalDuration = '0';
        if ($AgentAttendences) {
            foreach ($AgentAttendences as $k => $v) {
                $title = '';
                $a_date = date('Y-m-d', strtotime($v->start_date));
                if (! empty($v->end_time)) {
                    $etime = $v->end_time;
                } else {
                    $etime = date('H:i:s', strtotime('23:59:00'));
                }
                
                if ($a_date == date('Y-m-d') && empty($v->end_time)) {
                    $title .= '<span class="badge badge-pill badge-success pill-state">Online</span>';
                    $title .= "<br/>In Time: " . date('h:i A', strtotime($v->start_time));
                    $title .= "<br/>Out Time: N/A";
                    $title .= "<br/>Duration: N/A";
                } else {
                    $title .= '<span class="badge badge-pill badge-primary pill-state">Present</span>';
                    $title .= "<br/>In Time: " . date('h:i A', strtotime($v->start_time));
                    $title .= "<br/>Out Time: " . date('h:i A', strtotime($v->end_time));
                    $title .= "<br/>Duration: " . $v->total;
                }
                
                $totalDuration = $v->getDuration($total);
                $showData[$count]['title'] = $title;
                $showData[$count]['start'] = $a_date . 'T' . $v->start_time;
                $showData[$count]['end'] = $a_date . 'T' . $etime;
                $showData[$count]['start_time'] = $v->start_time;
                $showData[$count]['end_time'] = $etime;
                $showData[$count]['color'] = 'blue';
                $showData[$count]['type'] = 'date';
                $showData[$count]['roster_id'] = $v->id;
                $showData[$count]['slot_id'] = $v->slot_id;
                $showData[$count]['schedule_date'] = $v->schedule_date;
                $showData[$count]['memo'] = '';
                $showData[$count]['booking_type'] = 0;
                $showData[$count]['recurring'] = 0;
                $showData[$count]['start_date'] = $v->start_date;
                $showData[$count]['end_date'] = $v->end_date;
                $showData[$count]['agent_id'] = $v->agent_id;
                $showData[$count]['days'] = $days;
                $showData[$count]['order_url'] = '#';
                $total = $totalDuration;
                $count ++;
                // echo "<br>";
            }
        }
        $data['data'] = $showData;
        $data['duration'] = $totalDuration;
        echo $json = json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
