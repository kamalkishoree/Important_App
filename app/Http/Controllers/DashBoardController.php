<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\ClientPreference;
use App\Model\Order;
use App\Model\Task;
use App\Model\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // echo "<pre>";
        // print_r($request); die;
        //date for display tasks on map 

        if (isset($request->date)) {
              //echo $request->date; die;
             // $date = date('Y-m-d', strtotime($request->date));
              $date = Carbon::parse(strtotime($request->date))->format('Y-m-d');
             

        } else {
            
            $date = date('Y-m-d');
            
        }
        //echo $date; die; 
        //left side bar list for display all teams

        $teams  = Team::with(
            [
                'agents.order' => function ($o) use ($date) {
                    $o->whereDate('order_time', $date)->with('customer')->with('task.location');
                }
            ]
        )->get();

        foreach ($teams as $team) {
            $online  = 0;
            $offline = 0;
            $count   = 0;
            foreach ($team->agents as $agent) {
                $agent_task_count = 0;
                foreach ($agent->order as $tasks) {

                    $agent_task_count = $agent_task_count + count($tasks->task);
                }
                if ($agent->is_available == 1) {
                    $online++;
                } else {
                    $offline++;
                }
                $count++;
                $agent['free'] = count($agent->order) > 0 ? 'Busy' : 'Free';
                $agent['agent_task_count'] = $agent_task_count;
            }

            $team['online_agents']  = $online;
            $team['offline_agents'] = $offline;
            $agent['agent_count']   = $count;
        }


        //left side bar list for display unassigned team
        $unassigned = Agent::where('team_id', null)->with(['order' => function ($o) use ($date) {
            $o->whereDate('order_time', $date)->with('customer')->with('task.location');
        }])->get();

        $online  = 0;
        $offline = 0;
        $count   = 0;

        foreach ($unassigned as $agent) {

            $agent_task_count = 0;
            foreach ($agent->order as $tasks) {

                $agent_task_count = $agent_task_count + count($tasks->task);
            }

            if ($agent->is_available == 1) {
                $online++;
            } else {
                $offline++;
            }
            $count++;

            $agent['free'] = count($agent->order) > 0 ? 'Busy' : 'Free';
            $agent['online_agents']    = $online;
            $agent['offline_agents']   = $offline;
            $agent['agent_count']      = $count;
            $agent['agent_task_count'] = $agent_task_count;
        }



        //create array for map marker

        $allTasks = Order::whereDate('order_time', $date)->with(['customer', 'task.location', 'agent.team'])->get();

        $newmarker = [];

        foreach ($allTasks as $key => $tasks) {

            $append = [];
            foreach ($tasks->task as $task) {

                if ($task->task_type_id == 1) {
                    $name = 'Pickup';
                } elseif ($task->task_type_id == 2) {
                    $name = 'DropOff';
                } else {
                    $name = 'Appointment';
                }
                $append['task_type']             = $name;
                $append['task_id']               = $task->id;
                $append['latitude']              = floatval($task->location->latitude);
                $append['longitude']             = floatval($task->location->longitude);
                $append['address']               = $task->location->address;
                $append['task_type_id']          = $task->task_type_id;
                $append['task_status']           = (int)$task->task_status;
                $append['team_id']               = isset($tasks->driver_id) ? $tasks->agent->team_id : 0;
                $append['driver_name']           = isset($tasks->driver_id) ? $tasks->agent->name : '';
                $append['customer_name']         = $tasks->customer->name;
                $append['customer_phone_number'] = $tasks->customer->phone_number;

                array_push($newmarker, $append);
            }
        }
        // echo "<pre>";
        // print_r($newmarker); die;


        $unassigned->toArray();
        $teams->toArray();


        

        $agents = Agent::with('agentlog')->get()->toArray();
        $preference  = ClientPreference::where('id',1)->first(['theme','date_format','time_format']);
       // print_r($preference); die;
        return view('dashboard')->with(['teams' => $teams, 'newmarker' => $newmarker, 'unassigned' => $unassigned, 'agents' => $agents,'date'=> $date,'preference' =>$preference]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
