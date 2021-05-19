<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\ClientPreference;
use App\Model\Order;
use App\Model\Task;
use App\Model\Team;
use App\Model\LocationDistance;
use App\Model\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use PDF;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {         
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();  
        if (isset($request->date)) {

              $date = Carbon::parse(strtotime($request->date))->format('Y-m-d');
              
        } else {
            
            $date = date('Y-m-d');
            
        } 
        $startdate = date("Y-m-d 00:00:00", strtotime($date));
        $enddate = date("Y-m-d 23:59:59", strtotime($date));
        
       
        $startdate = Carbon::parse($startdate . $auth->timezone ?? 'UTC')->tz('UTC');
        $enddate = Carbon::parse($enddate . $auth->timezone ?? 'UTC')->tz('UTC');
       
        //left side bar list for display all teams    
        
        $teams  = Team::with(
            [
                'agents.order' => function ($o) use ($startdate,$enddate) {
                    $o->where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with('customer')->with('task.location');
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
        $unassigned = Agent::where('team_id', null)->with(['order' => function ($o) use ($startdate,$enddate) {
            $o->where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with('customer')->with('task.location');
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
        $allTasks = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with(['customer', 'task.location', 'agent.team'])->get();
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
                $append['latitude']              = isset($task->location->latitude) ? floatval($task->location->latitude):0.00;
                $append['longitude']             = isset($task->location->longitude) ? floatval($task->location->longitude): 0.00;
                $append['address']               = isset($task->location->address) ? $task->location->address : '';
                $append['task_type_id']          = isset($task->task_type_id) ? $task->task_type_id : '';
                $append['task_status']           = (int)$task->task_status;
                $append['team_id']               = isset($tasks->driver_id) ? $tasks->agent->team_id : 0;
                $append['driver_name']           = isset($tasks->driver_id) ? $tasks->agent->name : '';
                $append['driver_id']             = isset($tasks->driver_id) ? $tasks->driver_id : '';
                $append['customer_name']         = isset($tasks->customer->name)?$tasks->customer->name:'';
                $append['customer_phone_number'] = isset($tasks->customer->phone_number)?$tasks->customer->phone_number:'';
                $append['task_order']            = isset($task->task_order)?$task->task_order:0;
                array_push($newmarker, $append);
            }
        }

        $unassigned->toArray();
        $teams->toArray();

        $agents = Agent::with('agentlog')->get()->toArray();
        $preference  = ClientPreference::where('id',1)->first(['theme','date_format','time_format']);
    
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
                    usort($taskarray, function($a, $b) {
                        return $a['task_order'] <=> $b['task_order'];
                    });
                    if($date != date('Y-m-d'))
                    {
                        $singleagent['agentlog']['lat'] = $taskarray[0]['latitude'];
                        $singleagent['agentlog']['long'] = $taskarray[0]['longitude'];
                    }
                    $uniquedrivers[$j]['driver_detail'] = $singleagent['agentlog'];
                    $uniquedrivers[$j]['task_details'] = $taskarray;
                    $j++;                    
                }                
            }            
        }
        
        //for route optimization
        $routeoptimization = array();
        $taskarray = array();        
        foreach($uniquedrivers as $singledriver)
        { 
            if(count($singledriver['task_details'])>1)
            {   
                $points = array();
                $points[] = array(floatval($singledriver['driver_detail']['lat']),floatval($singledriver['driver_detail']['long']));
                $taskids = array();
                foreach($singledriver['task_details'] as $singletask)
                {
                    $points[] = array(floatval($singletask['latitude']),floatval($singletask['longitude']));
                    $taskids[] = $singletask['task_id'];
                }

                $taskarray[$singledriver['driver_detail']['agent_id']] = implode(',',$taskids);
                $routeoptimization[$singledriver['driver_detail']['agent_id']] = $points;
            }
        }

        //create distance matrix
        $distancematrix = array();
        foreach($routeoptimization as $key=>$value)
        {            
            $distancematrix[$key]['tasks'] = $taskarray[$key];            
            $distancematrix[$key]['distance'] = $routeoptimization[$key];
        }        

        $teamdata = $teams->toArray();
        
        foreach($teamdata as $k1=>$singleteam)
        {  
            foreach($singleteam['agents'] as $k2=>$singleagent)
            {    
                $teamdata[$k1]['agents'][$k2]['taskids']  = [];
                $teamdata[$k1]['agents'][$k2]['total_distance']  = '';          
                if(count($singleagent['order'])>0)
                {                   
                    //for calculating total distance                    
                    $sorted_orders = $this->splitOrder($singleagent['order']);                    
                    if(!empty($sorted_orders))
                    {    
                        $tasklistids = [];                    
                        foreach($sorted_orders as $singlesort)
                        {
                            $tasklistids[] = $singlesort['task'][0]['id'];
                        }
                        $teamdata[$k1]['agents'][$k2]['taskids'] = $tasklistids;
                        $driverlocation = [];
                        if($singleagent['is_available']==1){
                            $singleagentdetail = Agent::where('id',$singleagent['id'])->with('agentlog')->first();
                            $driverlocation['lat'] = $singleagentdetail->agentlog->lat;
                            $driverlocation['long'] = $singleagentdetail->agentlog->long;                            
                        }
                        $gettotal_distance = $this->getTotalDistance($tasklistids,$driverlocation);
                        $teamdata[$k1]['agents'][$k2]['total_distance']  = $gettotal_distance['total_distance_miles'];
                    }
                    $teamdata[$k1]['agents'][$k2]['order'] = $sorted_orders;                    
                }
            }            
        }

        //unassigned_orders 
        $unassigned_orders = array();
        $un_total_distance = '';
        $un_order  = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();    
            
        if(count($un_order)>=1)
        {
            $unassigned_orders = $this->splitOrder($un_order->toarray());
            if(count($unassigned_orders)>1)
            {
                $unassigned_distance_mat = array(); 
                $unassigned_points[] = array(floatval($unassigned_orders[0]['task'][0]['location']['latitude']),floatval($unassigned_orders[0]['task'][0]['location']['longitude']));
                $unassigned_taskids = array();
                $un_route = array();
                foreach($unassigned_orders as $singleua)
                {
                    $unassigned_taskids[] = $singleua['task'][0]['id'];
                    $unassigned_points[] = array(floatval($singleua['task'][0]['location']['latitude']),floatval($singleua['task'][0]['location']['longitude']));

                    //for drawing route
                    $s_task = $singleua['task'][0];
                    if ($s_task['task_type_id'] == 1) {
                        $nname = 'Pickup';
                    } elseif ($s_task['task_type_id'] == 2) {
                        $nname = 'DropOff';
                    } else {
                        $nname = 'Appointment';
                    }
                    $aappend = array();
                    $aappend['task_type']             = $nname;
                    $aappend['task_id']               =  $s_task['id'];
                    $aappend['latitude']              =  $s_task['location']['latitude'];
                    $aappend['longitude']             = $s_task['location']['longitude'];
                    $aappend['address']               = $s_task['location']['address'];
                    $aappend['task_type_id']          = $s_task['task_type_id'];
                    $aappend['task_status']           = $s_task['task_status'];
                    $aappend['team_id']               = 0;
                    $aappend['driver_name']           = '';
                    $aappend['driver_id']             = 0;
                    $aappend['customer_name']         = $singleua['customer']['name'];
                    $aappend['customer_phone_number'] = $singleua['customer']['phone_number'];
                    $aappend['task_order']            = $singleua['task_order'];
                    $un_route[] = $aappend;

                }                
                $unassigned_distance_mat['tasks'] = implode(',',$unassigned_taskids);
                $unassigned_distance_mat['distance'] = $unassigned_points;
                $distancematrix[0] = $unassigned_distance_mat;

                $first_un_loc = array('lat'=>floatval($unassigned_orders[0]['task'][0]['location']['latitude']),'long'=>floatval($unassigned_orders[0]['task'][0]['location']['longitude']));               
                $final_un_route['driver_detail'] = $first_un_loc;
                $final_un_route['task_details'] = $un_route;
                $uniquedrivers[] = $final_un_route;

                $gettotal_un_distance = $this->getTotalDistance($unassigned_taskids);

                $un_total_distance = $gettotal_un_distance['total_distance_miles'];

            }
        }

        $client = ClientPreference::where('id',1)->first(); 
        $googleapikey = $client->map_key_1;
        return view('dashboard')->with(['teams' => $teamdata, 'newmarker' => $newmarker, 'unassigned' => $unassigned, 'agents' => $agents,'date'=> $date,'preference' =>$preference, 'routedata' => $uniquedrivers,'distance_matrix' => $distancematrix, 'unassigned_orders' => $unassigned_orders,'unassigned_distance' => $un_total_distance,'map_key'=>$googleapikey]);
    }

    //function to create distance matrix
    public function distanceMatrix($pointarray,$taskids)
    {   
        $taskids = explode(',',$taskids);
        $pointarray[0][2] = 0;
        for ($t=1; $t < count($pointarray); $t++) {
            $pointarray[$t][2] = $taskids[$t - 1];
        }

        $matrixarray = array();            
        for ($i=0; $i < count($pointarray); $i++) { 

            for ($k=0; $k < count($pointarray); $k++) { 
                if($pointarray[$i][2] ==0 || $pointarray[$k][2]==0)
                {
                    if($i==$k)
                    {
                        $matrixarray[$i][$k] = 0; 
                    }elseif($i > $k)
                    {
                        $matrixarray[$i][$k] = $matrixarray[$k][$i];
                    }else{                    
                        $distance = $this->GoogleDistanceMatrix($pointarray[$i][0],$pointarray[$i][1],$pointarray[$k][0],$pointarray[$k][1]);   
                        $matrixarray[$i][$k] = $distance;
                    }
                }else{
                    if($i==$k)
                    {
                        $matrixarray[$i][$k] = 0; 
                    }elseif($i > $k)
                    {
                        $matrixarray[$i][$k] = $matrixarray[$k][$i];
                    }else{
                        $loc1 = $pointarray[$i][2];
                        $loc2 = $pointarray[$k][2];
                        //check if distance exist
                        $checkdistance = LocationDistance::where(['from_loc_id'=>$loc1,'to_loc_id'=>$loc2])->first();
                        if(isset($checkdistance->id))
                        {
                            $matrixarray[$i][$k] = $checkdistance->distance;
                        }else{
                            $distance = $this->GoogleDistanceMatrix($pointarray[$i][0],$pointarray[$i][1],$pointarray[$k][0],$pointarray[$k][1]);   
                            $matrixarray[$i][$k] = $distance;
                            $locdata = array('from_loc_id'=>$loc1,'to_loc_id'=>$loc2,'distance'=>$distance);
                            LocationDistance::create($locdata);
                        }                        
                    }
                }
                
            }            
        }        
        return $matrixarray;
    
    }

    public static function splitOrder($orders)
    {        
        $new_order = [];        
        if(is_array($orders) && count($orders)>0 && !empty($orders))
        {
            $counter = 0;
            foreach($orders as $order){
                foreach($order['task'] as $task){
                    $new_order[] = $order;
                    $new_order[$counter]['task_order'] = $task['task_order'];
                    unset($new_order[$counter]['task']);
                    $new_order[$counter]['task'][] = $task;
                    $counter++;    
                }                
            }

            //sort array
            usort($new_order, function($a, $b) {
                return $a['task_order'] <=> $b['task_order'];
            });
            return $new_order;
        }else{
            return $orders;
        }        
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

    // function to get distance between 2 location
    public function GoogleDistanceMatrix($lat1,$long1,$lat2,$long2)
    {
        //return $lat1.$long1;
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
        if(isset($value[0]->distance))
        {
            $totalDistance = $value[0]->distance->value;
        }else{
            $totalDistance = 0;
        }        
        return round($totalDistance);
    }

    //for optimizing route
    public function optimizeRoute(Request $request)
    {
        $driver_start_time = $request->driver_start_time;
        $task_duration = $request->task_duration;
        $brake_start_time = $request->brake_start_time;
        $brake_end_time = $request->brake_end_time;
        $driver_start_location = $request->driver_start_location;
        $driver_latitude = $request->latitude;
        $driver_longitude = $request->longitude;
        $taskids =  $request->route_taskids; 
        $agentid = $request->route_agentid; 
        $distancematrix = $request->distance_matrix;
        $distancematrixarray = json_decode($distancematrix);

        if($driver_start_location=='current')
        {
            if($agentid != 0)
            {
                $singleagentdetail = Agent::where('id',$agentid)->with('agentlog')->first();
                if($singleagentdetail->is_available == 1)
                {
                    $driver_lat = $singleagentdetail->agentlog->lat;
                    $driver_long = $singleagentdetail->agentlog->long;
                }else{
                    $driver_lat = $distancematrixarray[0][0];
                    $driver_long = $distancematrixarray[0][1];
                }
            }else{
                $driver_lat = $distancematrixarray[0][0];
                $driver_long = $distancematrixarray[0][1];
            }

        }elseif($driver_start_location=='task_location'){
            $startingtasklocation = Task::where('id',$request->task_start_location)->with('location')->first();
            $driver_lat = $startingtasklocation->location->latitude;
            $driver_long = $startingtasklocation->location->longitude;
            if($driver_lat==0 || $driver_long==0)
            {
                $driver_lat = $distancematrixarray[0][0];
                $driver_long = $distancematrixarray[0][1];
            }
        }else{            
            if($driver_latitude==0 || $driver_longitude==0)
            {
                $driver_lat = $distancematrixarray[0][0];
                $driver_long = $distancematrixarray[0][1];
            }else{
                $driver_lat = $driver_latitude;
                $driver_long = $driver_longitude;
            }
        }
        //arranging starting location in distance matrix
        $distancematrixarray[0][0] = $driver_lat;
        $distancematrixarray[0][1] = $driver_long;      
        
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $startdate = date("Y-m-d 00:00:00", strtotime($request->route_date));
        $enddate = date("Y-m-d 23:59:59", strtotime($request->route_date));
        $startdate = Carbon::parse($startdate . $auth->timezone ?? 'UTC')->tz('UTC');
        $enddate = Carbon::parse($enddate . $auth->timezone ?? 'UTC')->tz('UTC');
        $points = $distancematrixarray;      
        $distance_matrix = $this->distanceMatrix($points,$taskids);
        $payload = json_encode(array("data" => $distance_matrix));

        //api for getting optimize path
        $url = "https://optimizeroute.royodispatch.com/optimize";
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        if($result)
        {
            $taskids = explode(',',$taskids);
            $newtaskidorder = [];
            $newroute = json_decode($result);
            $routecount = count($newroute->data)-1;
            for ($i=1; $i < $routecount; $i++) {  
                $taskorder = [
                    'task_order'        => $i        
                ];  
                $index =  $newroute->data[$i]-1;             
                Task::where('id',$taskids[$index])->update($taskorder);
                $newtaskidorder[] = $taskids[$index];
            }

            $orderdetail = Task::where('id',$taskids[0])->with('order')->first();
            $orderdate =  date("Y-m-d", strtotime($orderdetail->order->order_time));            
            
            if($agentid!=0)
            {
                $agent = Agent::where('id',$agentid)->with(['order' => function ($o) use ($startdate,$enddate) {
                    $o->where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with('customer')->with('task.location');
                }])->with('agentlog')->first();                
                $agent = $agent->toArray();    
                if(count($agent['order'])>0)
                { 
                    $agent['order'] = $this->splitOrder($agent['order']);                    
                }

                $p=0;
                $driverstarttime = strtotime($driver_start_time);
                $braketimestart = strtotime($brake_start_time);
                $braketimeend = strtotime($brake_end_time);
                $taskdurationtime = strtotime($task_duration);
                $tasktime = 0;
                foreach($agent['order'] as $singleorder)
                {   
                    //brake time functionality
                    if($p>0)
                    {
                        $lat1 = $agent['order'][$p-1]['task'][0]['location']['latitude'];
                        $long1 = $agent['order'][$p-1]['task'][0]['location']['longitude'];
                        $lat2 = $agent['order'][$p]['task'][0]['location']['latitude'];
                        $long2 = $agent['order'][$p]['task'][0]['location']['longitude'];
                        $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                        $between_time = round($between_time['total_time']/60);                      

                        $task_duration_time = "+".$task_duration ." minutes";
                        $lasttasktime = strtotime($task_duration_time, $tasktime);
                        if($between_time==0)
                        {
                            $tasktime = $lasttasktime;
                            if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                            {
                                $tasktime = $lasttasktime;
                            }
                        }else{
                            $between_time_min = "+".$between_time ." minutes";
                            $tasktime = strtotime($between_time_min, $lasttasktime);
                            if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                            {
                                $tasktime = strtotime($between_time_min, $braketimeend);
                            }
                        }                        

                    }else{
                        $lat1 = $driver_lat;
                        $long1 = $driver_long;
                        $lat2 = $agent['order'][$p]['task'][0]['location']['latitude'];
                        $long2 = $agent['order'][$p]['task'][0]['location']['longitude'];
                        $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                        $between_time = round($between_time['total_time']/60);
                        if($between_time == 0)
                        {                            
                            $tasktime = $driverstarttime;
                            if($tasktime > $braketimestart)
                            {
                                $tasktime = $braketimeend;
                            }
                        }else{
                            $between_time_min = "+".$between_time." minutes";
                            $tasktime = strtotime($between_time_min, $driverstarttime);
                            if($tasktime > $braketimestart)
                            {
                                $tasktime = strtotime($between_time_min, $braketimeend);
                            }
                        }
                    }                   
                    $assignedtime = $agent['order'][$p]['task'][0]['assigned_time'];
                    $tskid = $agent['order'][$p]['task'][0]['id'];
                    $settime = date('Y-m-d', strtotime($request->route_date)).' '.date('H:i:s', $tasktime);
                    $assignedtime = Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC');

                    $updatetasktime = [
                        'assigned_time'                => $assignedtime,
                    ];

                    $orders = Task::where('id', $tskid)->update($updatetasktime);
                    $agent['order'][$p]['task'][0]['task_time'] = date("h:i a", $tasktime);                    
                    $p++;
                }
            }else{      //case of unassigned tasks with no driver
                $agent = array(
                    'id' => 0,
                    'name' => ''
                );

                $un_order  = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();                           
                $unassigned_tasks = $this->splitOrder($un_order->toarray());
                $p=0;
                $driverstarttime = strtotime($driver_start_time);
                $braketimestart = strtotime($brake_start_time);
                $braketimeend = strtotime($brake_end_time);
                $taskdurationtime = strtotime($task_duration);
                $tasktime = 0;
                foreach( $unassigned_tasks as $singleorder)
                {    
                    //brake time functionality
                    if($p>0)
                    {
                        $lat1 = $unassigned_tasks[$p-1]['task'][0]['location']['latitude'];
                        $long1 = $unassigned_tasks[$p-1]['task'][0]['location']['longitude'];
                        $lat2 = $unassigned_tasks[$p]['task'][0]['location']['latitude'];
                        $long2 = $unassigned_tasks[$p]['task'][0]['location']['longitude'];
                        $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                        $between_time = round($between_time['total_time']/60);
                        $between_time_min = "+".$between_time ." minutes";
                        //$lasttasktime = $tasktime + $taskdurationtime;
                        $task_duration_time = "+".$task_duration ." minutes";
                        $lasttasktime = strtotime($task_duration_time, $tasktime);
                        if($between_time==0)
                        {
                            $tasktime = $lasttasktime;
                            if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                            {
                                $tasktime = $braketimeend;
                            }
                        }else{
                            $tasktime = strtotime($between_time_min, $lasttasktime);
                            if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                            {
                                $tasktime = strtotime($between_time_min, $braketimeend);
                            }
                        }                       

                    }else{
                        $lat1 = $driver_lat;
                        $long1 = $driver_long;
                        $lat2 = $unassigned_tasks[$p]['task'][0]['location']['latitude'];
                        $long2 = $unassigned_tasks[$p]['task'][0]['location']['longitude'];
                        $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                        $between_time = round($between_time['total_time']/60);
                        $between_time_min = "+".$between_time." minutes";
                        if($between_time==0)
                        {
                            $tasktime = $driverstarttime;
                            if($tasktime > $braketimestart)
                            {
                                $tasktime = $braketimeend;
                            }
                        }else{
                            $tasktime = strtotime($between_time_min, $driverstarttime);
                            if($tasktime > $braketimestart)
                            {
                                $tasktime = strtotime($between_time_min, $braketimeend);
                            }
                        }
                    } 
                    
                    $assignedtime = $unassigned_tasks[$p]['task'][0]['assigned_time'];
                    $tskid = $unassigned_tasks[$p]['task'][0]['id'];

                    $settime = date('Y-m-d', strtotime($request->route_date)).' '.date('H:i:s', $tasktime);
                    $assignedtime = Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC');

                    $updatetasktime = [
                        'assigned_time' => $assignedtime,
                    ];
                   

                    $orders = Task::where('id', $tskid)->update($updatetasktime);
                    
                    $unassigned_tasks[$p]['task'][0]['task_time'] = date("h:i a", $tasktime);
                    $p++;
                }
                $agent['order'] = $unassigned_tasks;
            }           
            
            //getting all routes
            $allTasks = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with(['customer', 'task.location', 'agent.team'])->get();
            $allmarker = [];
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
                    $append['customer_name']         = isset($tasks->customer->name)? $tasks->customer->name:'';
                    $append['customer_phone_number'] = isset($tasks->customer->phone_number)?$tasks->customer->phone_number:'';
                    $append['task_order']            = isset($task->task_order)?$task->task_order:0;
                    array_push($allmarker, $append);
                }
            }

            $allagents = Agent::with('agentlog')->get()->toArray();
            $alldrivers = array();       
            $j = 0;
            foreach ($allagents as $singleagent) {
                if(is_array($singleagent['agentlog']))
                {
                    $alltaskarray = array();                
                    foreach($allmarker as $singlemark)
                    {
                        if($singlemark['driver_id'] == $singleagent['agentlog']['agent_id'])
                        {
                            $alltaskarray[] = $singlemark;                            
                        }
                    }
                    if(!empty($alltaskarray))
                    {
                        usort($alltaskarray, function($a, $b) {
                            return $a['task_order'] <=> $b['task_order'];
                        });
                        if($request->route_date != date('Y-m-d'))
                        {
                            $singleagent['agentlog']['lat'] = $alltaskarray[0]['latitude'];
                            $singleagent['agentlog']['long'] = $alltaskarray[0]['longitude'];
                        }
                        $alldrivers[$j]['driver_detail'] = $singleagent['agentlog'];
                        $alldrivers[$j]['task_details'] = $alltaskarray;
                        $j++;                    
                    }                    
                }                
            }   
            
            //unassigned_orders 
            $unassigned_orders = array();
            $un_order  = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();        
            
            if(count($un_order)>=1)
            {
                $unassigned_orders = $this->splitOrder($un_order->toarray());
                if(count($unassigned_orders)>1)
                {   
                    $un_route = array();
                    foreach($unassigned_orders as $singleua)
                    {    
                        //for drawing route
                        $s_task = $singleua['task'][0];
                        if ($s_task['task_type_id'] == 1) {
                            $nname = 'Pickup';
                        } elseif ($s_task['task_type_id'] == 2) {
                            $nname = 'DropOff';
                        } else {
                            $nname = 'Appointment';
                        }
                        $aappend = array();
                        $aappend['task_type']             = $nname;
                        $aappend['task_id']               =  $s_task['id'];
                        $aappend['latitude']              =  $s_task['location']['latitude'];
                        $aappend['longitude']             = $s_task['location']['longitude'];
                        $aappend['address']               = $s_task['location']['address'];
                        $aappend['task_type_id']          = $s_task['task_type_id'];
                        $aappend['task_status']           = $s_task['task_status'];
                        $aappend['team_id']               = 0;
                        $aappend['driver_name']           = '';
                        $aappend['driver_id']             = 0;
                        $aappend['customer_name']         = $singleua['customer']['name'];
                        $aappend['customer_phone_number'] = $singleua['customer']['phone_number'];
                        $aappend['task_order']            = $singleua['task_order'];
                        $un_route[] = $aappend;
                    }                   

                    $first_un_loc = array('lat'=>floatval($unassigned_orders[0]['task'][0]['location']['latitude']),'long'=>floatval($unassigned_orders[0]['task'][0]['location']['longitude']));                
                    $final_un_route['driver_detail'] = $first_un_loc;
                    $final_un_route['task_details'] = $un_route;
                    $alldrivers[] = $final_un_route;

                }
            }

            //calculate distance            
            $driverlocation = [];
            if($agentid != 0)
            {
                $singleagentdetail = Agent::where('id',$agentid)->with('agentlog')->first();
                if($singleagentdetail->is_available == 1)
                {
                    $driverlocation['lat'] = $singleagentdetail->agentlog->lat;
                    $driverlocation['long'] = $singleagentdetail->agentlog->long;
                }
            }
            $gettotal_distance = $this->getTotalDistance($taskids,$driverlocation);
            $totaldistance  = $gettotal_distance['total_distance_miles'];

            //sending silent notification
            if($agentid!=0)
            {
                $allcation_type = 'silent';
                //$randem     = rand(11111111, 99999999);
                $oneagent = Agent::where('id', $agentid)->first();
                $notification_data = [
                    'title'               => 'Update Order',
                    'body'                => 'Check All Details For This Request In App',
                    'order_id'            => '',
                    'driver_id'           => $agentid,
                    'notification_time'   => Carbon::now()->toDateTimeString(),
                    'type'                => $allcation_type,
                    'client_code'         => '',
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => '',
                ];           
                $this->sendsilentnotification($notification_data);           
            }

            $output = array();
            $output['tasklist'] = $agent;
            //$output['routedata'] = $routedata;
            $output['allroutedata'] = $alldrivers;
            $output['total_distance'] = $totaldistance;
            $output['taskids'] = $taskids;
            $output['agentid'] = $agentid;
            $output['distance_matrix'] = $distancematrix;
            $output['date'] = $request->route_date;
            $output['driver_position'] = $driver_lat.' '.$driver_long;
            $output['distancematrixjson'] = $payload;
            echo json_encode($output);   
            
        }else{
            echo "Try again later";
        }
    }

    //for drag and drop functionality
    public function arrangeRoute(Request $request)
    {        
        $taskids = explode(',',$request->taskids);
        $taskids = array_filter($taskids);

        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $startdate = date("Y-m-d 00:00:00", strtotime($request->date));
        $enddate = date("Y-m-d 23:59:59", strtotime($request->date));
        $startdate = Carbon::parse($startdate . $auth->timezone ?? 'UTC')->tz('UTC');
        $enddate = Carbon::parse($enddate . $auth->timezone ?? 'UTC')->tz('UTC');
        
        $agentid = $request->agentid;
        for ($i=0; $i < count($taskids); $i++) {
            $taskorder = [
                'task_order' => $i 
             ];
             Task::where('id',$taskids[$i])->update($taskorder);
        }

        $orderdetail = Task::where('id',$taskids[0])->with('order')->first();
        $orderdate =  date("Y-m-d", strtotime($orderdetail->order->order_time));

        //getting all routes
        $allTasks = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with(['customer', 'task.location', 'agent.team'])->get();
        
        $allmarker = [];
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
                $append['customer_name']         = isset($tasks->customer->name)?$tasks->customer->name:'';
                $append['customer_phone_number'] = isset($tasks->customer->phone_number)?$tasks->customer->phone_number:'';
                $append['task_order']            = $task->task_order;

                array_push($allmarker, $append);
            }
        }

        $allagents = Agent::with('agentlog')->get()->toArray();
        $alldrivers = array();       
        $j = 0;
        foreach ($allagents as $singleagent) {
            if(is_array($singleagent['agentlog']))
            {
                $alltaskarray = array();                
                foreach($allmarker as $singlemark)
                {
                    if($singlemark['driver_id'] == $singleagent['agentlog']['agent_id'])
                    {
                        $alltaskarray[] = $singlemark;                            
                    }
                }
                if(!empty($alltaskarray))
                {
                    usort($alltaskarray, function($a, $b) {
                        return $a['task_order'] <=> $b['task_order'];
                    });
                    if($request->date != date('Y-m-d'))
                    {
                        $singleagent['agentlog']['lat'] = $alltaskarray[0]['latitude'];
                        $singleagent['agentlog']['long'] = $alltaskarray[0]['longitude'];
                    }
                    $alldrivers[$j]['driver_detail'] = $singleagent['agentlog'];
                    $alldrivers[$j]['task_details'] = $alltaskarray;
                    $j++;                    
                }                    
            }                
        }  
        
        //unassigned_orders 
        $unassigned_orders = array();
        $un_order  = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();
        
        if(count($un_order)>=1)
        {
            $unassigned_orders = $this->splitOrder($un_order->toarray());
            if(count($unassigned_orders)>1)
            {   
                $un_route = array();
                foreach($unassigned_orders as $singleua)
                {                  

                    //for drawing route
                    $s_task = $singleua['task'][0];
                    if ($s_task['task_type_id'] == 1) {
                        $nname = 'Pickup';
                    } elseif ($s_task['task_type_id'] == 2) {
                        $nname = 'DropOff';
                    } else {
                        $nname = 'Appointment';
                    }
                    $aappend = array();
                    $aappend['task_type']             = $nname;
                    $aappend['task_id']               =  $s_task['id'];
                    $aappend['latitude']              =  $s_task['location']['latitude'];
                    $aappend['longitude']             = $s_task['location']['longitude'];
                    $aappend['address']               = $s_task['location']['address'];
                    $aappend['task_type_id']          = $s_task['task_type_id'];
                    $aappend['task_status']           = $s_task['task_status'];
                    $aappend['team_id']               = 0;
                    $aappend['driver_name']           = '';
                    $aappend['driver_id']             = 0;
                    $aappend['customer_name']         = $singleua['customer']['name'];
                    $aappend['customer_phone_number'] = $singleua['customer']['phone_number'];
                    $aappend['task_order']            = $singleua['task_order'];
                    $un_route[] = $aappend;

                }
                

                $first_un_loc = array('lat'=>floatval($unassigned_orders[0]['task'][0]['location']['latitude']),'long'=>floatval($unassigned_orders[0]['task'][0]['location']['longitude']));               
               $final_un_route['driver_detail'] = $first_un_loc;
               $final_un_route['task_details'] = $un_route;
               $alldrivers[] = $final_un_route;

            }
        }
        
        //calculating distance        
        $driverlocation = [];
        if($agentid != 0)
        {
            $singleagentdetail = Agent::where('id',$agentid)->with('agentlog')->first();
            if($singleagentdetail->is_available == 1)
            {
                $driverlocation['lat'] = $singleagentdetail->agentlog->lat;
                $driverlocation['long'] = $singleagentdetail->agentlog->long;
            }
        }

        $gettotal_distance = $this->getTotalDistance($taskids,$driverlocation);
        $distance  = $gettotal_distance['total_distance_miles'];

        if($agentid!=0)
        {
            $allcation_type = 'silent';
            //$randem     = rand(11111111, 99999999);
            $oneagent = Agent::where('id', $agentid)->first();
            $notification_data = [
                'title'               => 'Update Order',
                'body'                => 'Check All Details For This Request In App',
                'order_id'            => '',
                'driver_id'           => $agentid,
                'notification_time'   => Carbon::now()->toDateTimeString(),
                'type'                => $allcation_type,
                'client_code'         => '',
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => '',
            ];           
            $this->sendsilentnotification($notification_data);           
        }

        $output = array();
        $output['allroutedata'] = $alldrivers;    
        $output['total_distance'] = $distance;        
        echo json_encode($output);   
                    
    }

    //for optimizing after drag drop
    public function optimizeArrangeRoute(Request $request)
    {
        $driver_start_time = $request->driver_start_time;
        $task_duration = $request->task_duration;
        $brake_start_time = $request->brake_start_time;
        $brake_end_time = $request->brake_end_time;
        $driver_start_location = $request->driver_start_location;
        $driver_latitude = $request->latitude;
        $driver_longitude = $request->longitude;
        
        $agentid = $request->route_agentid; 
        $distancematrix = $request->distance_matrix;

        $taskids = explode(',',$request->route_taskids);
        $taskids = array_filter($taskids);
        $firsttaskdetail = Task::where('id',$taskids[0])->with('location')->first();            
        
        if($driver_start_location=='current')
        {
            if($agentid != 0)
            {
                $singleagentdetail = Agent::where('id',$agentid)->with('agentlog')->first();
                if($singleagentdetail->is_available == 1)
                {
                    $driver_lat = $singleagentdetail->agentlog->lat;
                    $driver_long = $singleagentdetail->agentlog->long;
                }else{
                    $driver_lat = $firsttaskdetail->location->latitude;
                    $driver_long = $firsttaskdetail->location->longitude;
                }
            }else{
                $driver_lat = $firsttaskdetail->location->latitude;
                $driver_long = $firsttaskdetail->location->longitude;
            }

        }elseif($driver_start_location=='task_location'){
            $startingtasklocation = Task::where('id',$request->task_start_location)->with('location')->first();
            $driver_lat = $startingtasklocation->location->latitude;
            $driver_long = $startingtasklocation->location->longitude;
            if($driver_lat==0 || $driver_long==0)
            {
                $driver_lat = $firsttaskdetail->location->latitude;
                $driver_long = $firsttaskdetail->location->longitude;
            }

        }else{            
            if($driver_latitude==0 || $driver_longitude==0)
            {
                $driver_lat = $firsttaskdetail->location->latitude;
                $driver_long = $firsttaskdetail->location->longitude;
            }else{
                $driver_lat = $driver_latitude;
                $driver_long = $driver_longitude;
            }
        }        
        
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $startdate = date("Y-m-d 00:00:00", strtotime($request->route_date));
        $enddate = date("Y-m-d 23:59:59", strtotime($request->route_date));
        $startdate = Carbon::parse($startdate . $auth->timezone ?? 'UTC')->tz('UTC');
        $enddate = Carbon::parse($enddate . $auth->timezone ?? 'UTC')->tz('UTC');                
            
        if($agentid!=0)
        {
            $agent = Agent::where('id',$agentid)->with(['order' => function ($o) use ($startdate,$enddate) {
                $o->where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with('customer')->with('task.location');
            }])->with('agentlog')->first();
            $agent = $agent->toArray();            

            if(count($agent['order'])>0)
            { 
                $agent['order'] = $this->splitOrder($agent['order']);                    
            }

            $p=0;            
            $driverstarttime = strtotime($driver_start_time);
            $braketimestart = strtotime($brake_start_time);
            $braketimeend = strtotime($brake_end_time);
            $taskdurationtime = strtotime($task_duration);
            $tasktime = 0;
            foreach($agent['order'] as $singleorder)
            {   
                //brake time functionality
                if($p>0)
                {
                    $lat1 = $agent['order'][$p-1]['task'][0]['location']['latitude'];
                    $long1 = $agent['order'][$p-1]['task'][0]['location']['longitude'];
                    $lat2 = $agent['order'][$p]['task'][0]['location']['latitude'];
                    $long2 = $agent['order'][$p]['task'][0]['location']['longitude'];
                    $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                    $between_time = round($between_time['total_time']/60);
                    //$lasttasktime = $tasktime + $taskdurationtime;
                    $task_duration_time = "+".$task_duration ." minutes";
                    $lasttasktime = strtotime($task_duration_time, $tasktime);
                    if($between_time==0)
                    {
                        $tasktime = $lasttasktime;
                        if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                        {
                            $tasktime = $lasttasktime;
                        }
                    }else{
                        $between_time_min = "+".$between_time ." minutes";
                        $tasktime = strtotime($between_time_min, $lasttasktime);
                        if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                        {
                            $tasktime = strtotime($between_time_min, $braketimeend);
                        }
                    }
                    

                }else{
                    $lat1 = $driver_lat;
                    $long1 = $driver_long;
                    $lat2 = $agent['order'][$p]['task'][0]['location']['latitude'];
                    $long2 = $agent['order'][$p]['task'][0]['location']['longitude'];
                    $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                    $between_time = round($between_time['total_time']/60);
                    if($between_time == 0)
                    {                        
                        $tasktime = $driverstarttime;
                        if($tasktime > $braketimestart)
                        {
                            $tasktime = $braketimeend;
                        }

                    }else{
                        $between_time_min = "+".$between_time." minutes";
                        $tasktime = strtotime($between_time_min, $driverstarttime);
                        if($tasktime > $braketimestart)
                        {
                            $tasktime = strtotime($between_time_min, $braketimeend);
                        }
                    }
                }

                $assignedtime = $agent['order'][$p]['task'][0]['assigned_time'];                
                $tskid = $agent['order'][$p]['task'][0]['id'];
                
                $settime = date('Y-m-d', strtotime($request->route_date)).' '.date('H:i:s', $tasktime);
                $assignedtime = Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC');

                $updatetasktime = [
                    'assigned_time'                => $assignedtime,
                ];

                $orders = Task::where('id', $tskid)->update($updatetasktime);
                $agent['order'][$p]['task'][0]['task_time'] = date("h:i a", $tasktime);                    
                $p++;
            }
        }else{      //case of unassigned tasks with no driver
            $agent = array(
                'id' => 0,
                'name' => ''
            );             
            $un_order  = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();                           
            $unassigned_tasks = $this->splitOrder($un_order->toarray());
            $p=0;
            $driverstarttime = strtotime($driver_start_time);
            $braketimestart = strtotime($brake_start_time);
            $braketimeend = strtotime($brake_end_time);
            $taskdurationtime = strtotime($task_duration);
            $tasktime = 0;
            foreach( $unassigned_tasks as $singleorder)
            {    
                //brake time functionality
                if($p>0)
                {
                    $lat1 = $unassigned_tasks[$p-1]['task'][0]['location']['latitude'];
                    $long1 = $unassigned_tasks[$p-1]['task'][0]['location']['longitude'];
                    $lat2 = $unassigned_tasks[$p]['task'][0]['location']['latitude'];
                    $long2 = $unassigned_tasks[$p]['task'][0]['location']['longitude'];
                    $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                    $between_time = round($between_time['total_time']/60);
                    $between_time_min = "+".$between_time ." minutes";
                   // $lasttasktime = $tasktime + $taskdurationtime;
                    $task_duration_time = "+".$task_duration ." minutes";
                    $lasttasktime = strtotime($task_duration_time, $tasktime);
                    if($between_time==0)
                    {
                        $tasktime = $lasttasktime;
                        if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                        {
                            $tasktime = $braketimeend;
                        }
                    }else{
                        $tasktime = strtotime($between_time_min, $lasttasktime);
                        if(($tasktime > $braketimestart) && ($tasktime < $braketimeend))
                        {
                            $tasktime = strtotime($between_time_min, $braketimeend);
                        }
                    }
                }else{
                    $lat1 = $driver_lat;
                    $long1 = $driver_long;
                    $lat2 = $unassigned_tasks[$p]['task'][0]['location']['latitude'];
                    $long2 = $unassigned_tasks[$p]['task'][0]['location']['longitude'];
                    $between_time = $this->GetTotalTime($lat1,$long1,$lat2,$long2);
                    $between_time = round($between_time['total_time']/60);
                    $between_time_min = "+".$between_time." minutes";
                    if($between_time==0)
                    {
                        $tasktime = $driverstarttime;
                        if($tasktime > $braketimestart)
                        {
                            $tasktime = $braketimeend;
                        }
                    }else{
                        $tasktime = strtotime($between_time_min, $driverstarttime);
                        if($tasktime > $braketimestart)
                        {
                            $tasktime = strtotime($between_time_min, $braketimeend);
                        }
                    }                       
                    
                } 
                
                $assignedtask = $unassigned_tasks[$p]['task'][0]['assigned_time'];                
                $tskid = $unassigned_tasks[$p]['task'][0]['id'];
                
                $settime = date('Y-m-d', strtotime($request->route_date)).' '.date('H:i:s', $tasktime);
                $assignedtime = Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC');

                $updatetasktime = [
                    'assigned_time'                => $assignedtime,
                ];

                $orders = Task::where('id', $tskid)->update($updatetasktime);                
                $unassigned_tasks[$p]['task'][0]['task_time'] = date("h:i a", $tasktime);                                                            
                $p++;
            }
            $agent['order'] = $unassigned_tasks;
        }
        $output = array();
        $output['tasklist'] = $agent;
        //$output['routedata'] = $routedata;
        $output['allroutedata'] = "";        
        $output['taskids'] = $taskids;
        $output['agentid'] = $agentid;
        $output['distance_matrix'] = $distancematrix;
        $output['date'] = $request->route_date;
        echo json_encode($output);   
    }

    public function getTotalDistance($taskids=null,$driverlocation=null)
    {       
        $points = array();
        for($i=0;$i<count($taskids);$i++)
        {
            $Taskdetail = Task::where('id',$taskids[$i])->with('location')->first();            
            $points[$i]['lat'] = $Taskdetail->location->latitude;
            $points[$i]['long'] = $Taskdetail->location->longitude;
            $points[$i]['loc_id'] = $Taskdetail->location_id;
            $points[$i]['taskid'] = $Taskdetail->id;

        }
        $totaldistance = 0;
        $distancearray  = [];
        if(isset($driverlocation['lat']))
        {            
            $distance = $this->GoogleDistanceMatrix($driverlocation['lat'],$driverlocation['long'],$points[0]['lat'],$points[0]['long']);   
            $totaldistance += $distance;     
            $distancearray[] = $distance;    
        }else{
            $distancearray[] = 0;
        }
        for($j=1; $j<count($points); $j++)
        {
            $loc1 = $points[$j-1]['loc_id'];
            $loc2 = $points[$j]['loc_id'];
            //check if distance exist
            $checkdistance = LocationDistance::where(['from_loc_id'=>$loc1,'to_loc_id'=>$loc2])->first();
            if(isset($checkdistance->id))
            {
                $totaldistance += $checkdistance->distance;    
                $distancearray[] = $checkdistance->distance;            
            }else{
                $distance = $this->GoogleDistanceMatrix($points[$j-1]['lat'],$points[$j-1]['long'],$points[$j]['lat'],$points[$j]['long']);   
                $totaldistance += $distance;      
                $distancearray[] = $distance;          
                $locdata = array('from_loc_id'=>$loc1,'to_loc_id'=>$loc2,'distance'=>$distance);
                LocationDistance::create($locdata);
            }
        }
        $distance_in_km = number_format($totaldistance/1000,2);
        $distance_in_miles = number_format($totaldistance/1609.344,2);
        $output['total_distance'] = $totaldistance;
        $output['distance'] = $distancearray;
        $output['total_distance_km'] = $distance_in_km . 'km';
        $output['total_distance_miles'] = $distance_in_miles . 'miles';
        return $output;
        
    }

    public function ExportPdfPath(Request $request)
    {

        $taskids = explode(',',$request->taskids);
        $agentid = $request->agentid;
        $origin = [];
        $destination = [];
        $waypoints = [];
        $location = [];
        $agent_name = "";
        if($agentid != 0)
        {
            $singleagentdetail = Agent::where('id',$agentid)->with('agentlog')->first();
            if($singleagentdetail->is_available == 1)
            {
                $origin['lat'] = $singleagentdetail->agentlog->lat;
                $origin['long'] = $singleagentdetail->agentlog->long;
            }
            $agent_name = $singleagentdetail->name;
        }

        $totallocations = count($taskids);
        $w=0;
        for($i=0;$i<$totallocations;$i++)
        {

            $Taskdetail = Task::where('id',$taskids[$i])->with('location')->first();
            $location[] = $Taskdetail->location->address;
            if($i == $totallocations-1)
            {
                $destination['lat'] = $Taskdetail->location->latitude;
                $destination['long'] = $Taskdetail->location->longitude;
            }elseif(empty($origin) && $i == 0)
            {
                $origin['lat'] = $Taskdetail->location->latitude;
                $origin['long'] = $Taskdetail->location->longitude;
            }else{
                $waypoints[$w]['lat'] = $Taskdetail->location->latitude;
                $waypoints[$w]['long'] = $Taskdetail->location->longitude;
                $w++;
            }           

        }

        $routedetail = $this->GetRouteDirection($origin,$destination,$waypoints);
        
        $p['route']=$routedetail;
        $p['path'] = $location;
        $p['date'] = $request->date;
        $p['agent_name'] = $agent_name;
        // $pdf_doc = PDF::loadView('pdf',$p);
        // return $pdf_doc->download('routedetail.pdf');        
        echo json_encode($p);
    }

    public function generatePdf(Request $request)
    {   
        if(isset(Auth::user()->logo)){
        $urlImg = Storage::disk('s3')->url(Auth::user()->logo);
        }
        $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fit/300/100/sm/0/plain/';
        $image = $imgproxyurl.$urlImg; 

        $result = json_decode($request->pdfdata);        
        $p['route'] = $result->route;
        $p['path'] = $result->path;   
        $p['date'] = $result->date;
        $p['agent_name'] = $result->agent_name;    
        $p['logo'] =  $image;
        // $pdf_doc = PDF::loadView('pdf',$p);
        // return $pdf_doc->download('routedetail.pdf');
         return view('pdf',$p);
    }

    public function GetRouteDirection($origin,$destination,$midpoints)
    {
        $lat1 = $origin['lat'];
        $long1 = $origin['long'];
        $lat2 = $destination['lat'];
        $long2 = $destination['long'];
        $waypoint = "";
        if(!empty($midpoints))
        {
            $via = [];
            for($i=0;$i<count($midpoints);$i++)
            {
                $via[]="via:".$midpoints[$i]['lat'].",".$midpoints[$i]['long'];
            }
            $waypoints = implode('|',$via);
            $waypoint = "&waypoints=".$waypoints;
        }        
        $client = ClientPreference::where('id',1)->first();        
        $ch = curl_init();
        $headers = array('Accept: application/json',
                   'Content-Type: application/json',
                   );                   
        $url =  'https://maps.googleapis.com/maps/api/directions/json?origin='.$lat1.','.$long1.'&destination='.$lat2.','.$long2.'&key='.$client->map_key_1.$waypoint;
        
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch); // Close the connection
        
        $routes = $result->routes[0]->legs[0]->steps;
        $output = array();
        if(isset($routes))
        {   
            $j=0;
            foreach($routes as $singlestep)
            {
                $output[$j]['distance'] = $singlestep->distance->text;
                $output[$j]['duration'] = $singlestep->duration->text;
                $output[$j]['turn'] = $singlestep->html_instructions;
                $j++;
            }
        }
        return $output;
    }

    public function GetTotalTime($lat1,$long1,$lat2,$long2)
    {          
        $client = ClientPreference::where('id',1)->first();        
        $ch = curl_init();
        $headers = array('Accept: application/json',
                   'Content-Type: application/json',
                   );                   
        $url =  'https://maps.googleapis.com/maps/api/directions/json?origin='.$lat1.','.$long1.'&destination='.$lat2.','.$long2.'&key='.$client->map_key_1;        
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch); // Close the connection
                
        $routes = $result->routes[0]->legs[0]->steps;
        $time = $result->routes[0]->legs[0]->duration->value;

        $output = array();
        $output['total_time'] = $time;        
        return $output;
    }

    public function getTaskDetails(Request $request)
    {
        $taskids = explode(',',$request->taskids);
        $taskids = array_filter($taskids);

        $taskdetails = [];
        $html = "";
        for($i=0;$i<count($taskids);$i++)
        {
            $singletaskdetail = Task::where('id',$taskids[$i])->with('location')->first();
            $taskdetails[] = $singletaskdetail->toArray();
            // $html .= '<option value="">'.$singletaskdetail->task_type_id.'</option>';
        }
        echo json_encode($taskdetails);

          
    }

    public function sendsilentnotification($notification_data)
    {  
        $new = [];
        array_push($new,$notification_data['device_token']);
        if(isset($new)){
            fcm()
            ->to($new) // $recipients must an array
            ->priority('normal')
            ->timeToLive(0)
            ->data($notification_data)
            ->notification([
                'title' => 'Silent Notification',
                'body'  =>  'Check All Details For This Request In App',
                'sound' =>   '',
            ])
            ->send();
        }          
    }

    

}
