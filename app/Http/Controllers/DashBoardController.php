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
        //echo "<pre>"; print_r($allTasks); die;
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
                $append['driver_id']             = isset($tasks->driver_id) ? $tasks->driver_id : '';
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

    //    echo "<pre>"; 
    //    echo "tasks";
    //    print_r($newmarker); die;
    //    echo "agent";

       //print_r($agents); die; 

    
       $uniquedrivers = array();       
       $j = 0;
        foreach ($agents as $singleagent) {
            if(is_array($singleagent['agentlog']))
            {
                
                $taskarray = array();
                foreach($newmarker as $singlemark)
                {
                    if($singlemark['driver_id'] == $singleagent['agentlog']['agent_id'])
                    {
                        $taskarray[] = $singlemark;
                        
                    }
                }
                if(!empty($taskarray))
                {
                    $uniquedrivers[$j]['driver_detail'] = $singleagent['agentlog'];
                    $uniquedrivers[$j]['task_details'] = $taskarray;
                    $j++;                    
                }
                
            }

            
        }
        
        // echo "<pre>";
        // print_r($uniquedrivers); die;

        //for route optimization
        $routeoptimization = array();
        foreach($uniquedrivers as $singledriver)
        {
            if(count($singledriver['task_details'])>1)
            {   
                $points[] = array($singledriver['driver_detail']['lat'],$singledriver['driver_detail']['long']);
                foreach($singledriver['task_details'] as $singletask)
                {
                    $points[] = array($singletask['latitude'],$singletask['longitude']);
                }
                


                $routeoptimization[$singledriver['driver_detail']['agent_id']] = $points;
            }
        }

        //create distance matrix
        foreach($routeoptimization as $key=>$value)
        {
            $matrixarray = array();

            
            for ($i=0; $i < count($value); $i++) { 

                for ($k=0; $k < count($value); $k++) { 
                    if($i==$k)
                    {
                        $matrixarray[$i][$k] = 0; 
                    }elseif($i > $k)
                    {
                       $matrixarray[$i][$k] = $matrixarray[$k][$i];
                    }else{
                        $distance = $this->GoogleDistanceMatrix($value[$i][0],$value[$i][1],$value[$k][0],$value[$k][1] );                    
                        $matrixarray[$i][$k] = $distance;
                    }
                    // $distance = $this->GoogleDistanceMatrix($value[$i][0],$value[$i][1],$value[$k][0],$value[$k][1] );
                    
                    // $matrixarray[$i][$k] = $distance; 
                }
                
            }

            //echo json_encode($matrixarray);

        }

       



        //    echo "<pre>"; 
        //    print_r($routeoptimization); die;


        
        return view('dashboard')->with(['teams' => $teams, 'newmarker' => $newmarker, 'unassigned' => $unassigned, 'agents' => $agents,'date'=> $date,'preference' =>$preference, 'routedata' => $uniquedrivers]);
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

    public function GoogleDistanceMatrix($lat1,$long1,$lat2,$long2)
    {
        $client = ClientPreference::where('id',1)->first();        
        $ch = curl_init();
        $headers = array('Accept: application/json',
                   'Content-Type: application/json',
                   );
        $url =  'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$lat1.','.$long1.'&destinations='.$lat2.','.$long2.'&key='.$client->map_key_1.'';
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch); // Close the connection
        
        $value =   $result->rows[0]->elements;
        $totalDistance = $value[0]->distance->value;
        return round($totalDistance);
      
        

    }

}
