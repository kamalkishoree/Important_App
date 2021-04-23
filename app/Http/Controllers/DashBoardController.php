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

      //  dd($teams);


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
       //echo "<pre>"; print_r($allTasks->toArray()); die;
        // dd($allTasks);
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

        // echo "<pre>";
        // print_r($newmarker); die;

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
        // echo "<pre>";
        // print_r($uniquedrivers); die;

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


        // echo "<pre>";
        // print_r($uniquedrivers); die;

        // echo "<pre>";
        // print_r($routeoptimization); die;

        //create distance matrix
        $distancematrix = array();
        foreach($routeoptimization as $key=>$value)
        {
            // $matrixarray = array();

            
            // for ($i=0; $i < count($value); $i++) { 

            //     for ($k=0; $k < count($value); $k++) { 
            //         if($i==$k)
            //         {
            //             $matrixarray[$i][$k] = 0; 
            //         }elseif($i > $k)
            //         {
            //            $matrixarray[$i][$k] = $matrixarray[$k][$i];
            //         }else{
            //             $distance = $this->GoogleDistanceMatrix($value[$i][0],$value[$i][1],$value[$k][0],$value[$k][1] );                    
            //             $matrixarray[$i][$k] = $distance;
                        
            //             //$matrixarray[$i][$k] = 0;
            //         }
            //         // $distance = $this->GoogleDistanceMatrix($value[$i][0],$value[$i][1],$value[$k][0],$value[$k][1] );
                    
            //         // $matrixarray[$i][$k] = $distance; 
            //     }
                
            // }
            $distancematrix[$key]['tasks'] = $taskarray[$key];
            //$distancematrix[$key]['distance'] = $matrixarray;
            $distancematrix[$key]['distance'] = $routeoptimization[$key];
        }
        // echo "<pre>";
        // echo json_encode($distancematrix[9]['distance']);
        // print_r($distancematrix); die;
        

        $teamdata = $teams->toArray();

        foreach($teamdata as $k1=>$singleteam)
        {  
            foreach($singleteam['agents'] as $k2=>$singleagent)
            {                
                if(count($singleagent['order'])>0)
                { 
                    $teamdata[$k1]['agents'][$k2]['order'] = $this->splitOrder($singleagent['order']);                    
                }
            }            
        }

        //unassigned_orders 
        $unassigned_orders = array();
        $un_order  = Order::whereDate('order_time', $date)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();        
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
               // [driver_detail] => $first_un_loc; $un_route
               $final_un_route['driver_detail'] = $first_un_loc;
               $final_un_route['task_details'] = $un_route;
               $uniquedrivers[] = $final_un_route;

            }
        }

        // echo "<pre>";
        //    print_r($unassigned_orders); 
           
        //    print_r($uniquedrivers);
        //    //print_r($distancematrix);
        //    die;
        
        return view('dashboard')->with(['teams' => $teamdata, 'newmarker' => $newmarker, 'unassigned' => $unassigned, 'agents' => $agents,'date'=> $date,'preference' =>$preference, 'routedata' => $uniquedrivers,'distance_matrix' => $distancematrix, 'unassigned_orders' => $unassigned_orders]);
    }


    public function distanceMatrix($pointarray)
    {
        $distancematrix = array();
        foreach($pointarray as $key=>$value)
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
                        $distance = $this->GoogleDistanceMatrix('$value[$i][0]','$value[$i][1]','$value[$k][0]','$value[$k][1]' );                    
                        $matrixarray[$i][$k] = $distance;
                        
                        //$matrixarray[$i][$k] = 0;
                    }
                    // $distance = $this->GoogleDistanceMatrix($value[$i][0],$value[$i][1],$value[$k][0],$value[$k][1] );
                    
                    // $matrixarray[$i][$k] = $distance; 
                }
                
            }
            
            return $matrixarray;
        }
    }

    public static function splitOrder($orders){
        // array //
        $new_order = [];
        
        if(is_array($orders) && count($orders)>0 && !empty($orders))
        {
            $counter = 0;
            foreach($orders as $order){
               // print_r($order); die;
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
        if(isset($value[0]->distance))
        {
            $totalDistance = $value[0]->distance->value;
        }else{
            $totalDistance = 0;
        }
        
        return round($totalDistance);
      
        

    }

    public function optimizeRoute(Request $request)
    {
        $taskids =  $request->taskids; 
        $agentid = $request->agentid; 
        
        //$distance_matrix = json_decode($request->distance); 
        $points = json_decode($request->distance); 
        $distance_matrix = $this->distanceMatrix($points);
        $payload = json_encode(array("data" => $distance_matrix));
        //return $distance_matrix;
        //hit the api for getting optimize path

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
            $newroute = json_decode($result);
            $routecount = count($newroute->data)-1;
            for ($i=1; $i < $routecount; $i++) {                 
                $taskorder = [
                    'task_order'        => $newroute->data[$i]        
                 ];                
                 Task::where('id',$taskids[$i-1])->update($taskorder);
            }

            $orderdetail = Task::where('id',$taskids[0])->with('order')->first();
            $orderdate =  date("Y-m-d", strtotime($orderdetail->order->order_time));            
            
            if($agentid!=0)
            {
                $agent = Agent::where('id',$agentid)->with(['order' => function ($o) use ($orderdate) {
                    $o->whereDate('order_time', $orderdate)->with('customer')->with('task.location');
                }])->with('agentlog')->first();

                $agent = $agent->toArray();            

                if(count($agent['order'])>0)
                { 
                    $agent['order'] = $this->splitOrder($agent['order']);                    
                }

                $p=0;
                foreach($agent['order'] as $singleorder)
                {    
                    $agent['order'][$p]['task'][0]['task_time'] = date("h:i a", strtotime($singleorder['task'][0]['created_at']));
                    $p++;
                }
            }else{      //case of unassigned tasks with no driver
                $agent = array(
                    'id' => 0,
                    'name' => ''

                );

                $un_order  = Order::whereDate('order_time', $orderdate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();        
                
                    $unassigned_tasks = $this->splitOrder($un_order->toarray());
                    $p=0;
                    foreach( $unassigned_tasks as $singleorder)
                    {    
                        $unassigned_tasks[$p]['task'][0]['task_time'] = date("h:i a", strtotime($singleorder['task'][0]['created_at']));
                        $p++;
                    }
                    $agent['order'] = $unassigned_tasks;
                   
                    //$first_un_loc = array('lat'=>floatval($unassigned_orders[0]['task'][0]['location']['latitude']),'long'=>floatval($unassigned_orders[0]['task'][0]['location']['longitude']));  
            }
            // echo "<pre>";
            // print_r($agent); die;
            

            //map single route data
            // $newmarker = [];
            // $append = [];
            // foreach ($agent['order'] as $singleorder) {
            //     $taskdetail = $singleorder['task'][0];
            //     if ($taskdetail['task_type_id'] == 1) {
            //         $name = 'Pickup';
            //     } elseif ($taskdetail['task_type_id'] == 2) {
            //         $name = 'DropOff';
            //     } else {
            //         $name = 'Appointment';
            //     }
            //     $append['task_type']             = $name;
            //     $append['task_id']               = $taskdetail['id'];
            //     $append['latitude']              = floatval($taskdetail['location']['latitude']);
            //     $append['longitude']             = floatval($taskdetail['location']['longitude']);
            //     $append['address']               = $taskdetail['location']['address'];
            //     $append['task_type_id']          = $taskdetail['task_type_id'];
            //     $append['task_status']           = (int)$taskdetail['task_status'];
            //     $append['team_id']               = $agent['team_id'];
            //     $append['driver_name']           = $agent['name'];
            //     $append['driver_id']             = $agent['id'];
            //     $append['customer_name']         = $singleorder['customer']['name'];
            //     $append['customer_phone_number'] = $singleorder['customer']['phone_number'];
            //     $append['task_order']            = $taskdetail['task_order'];

            //     array_push($newmarker, $append);
            // }            

            // $routedata = array();            
            //     if(is_array($agent['agentlog']))
            //     {
            //         $taskarray = array();                
            //         foreach($newmarker as $singlemark)
            //         {
            //             if($singlemark['driver_id'] == $agent['agentlog']['agent_id'])
            //             {
            //                 $taskarray[] = $singlemark;                            
            //             }
            //         }
            //         if(!empty($taskarray))
            //         {                        
            //             if($orderdate != date('Y-m-d'))
            //             {
            //                 $agent['agentlog']['lat'] = $taskarray[0]['latitude'];
            //                 $agent['agentlog']['long'] = $taskarray[0]['longitude'];
            //             }
            //             $routedata['driver_detail'] = $agent['agentlog'];
            //             $routedata['task_details'] = $taskarray;                                  
            //         }                    
            //     }
            //map single route data ends
            
            //getting all routes
            $allTasks = Order::whereDate('order_time', $orderdate)->with(['customer', 'task.location', 'agent.team'])->get();
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
                        if($orderdate != date('Y-m-d'))
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
            $un_order  = Order::whereDate('order_time', $orderdate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();        
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
                // [driver_detail] => $first_un_loc; $un_route
                $final_un_route['driver_detail'] = $first_un_loc;
                $final_un_route['task_details'] = $un_route;
                $alldrivers[] = $final_un_route;

                }
            }

            // echo "<pre>";
            // print_r($agent); die;

            $output = array();
            $output['tasklist'] = $agent;
            //$output['routedata'] = $routedata;
            $output['allroutedata'] = $alldrivers;            
            echo json_encode($output);   
            
        }else{
            echo "Try again later";
        }
    }

    public function arrangeRoute(Request $request)
    {        
        $taskids = explode(',',$request->taskids);        
        $taskids = array_filter($taskids);
        for ($i=0; $i < count($taskids); $i++) {                 
            $taskorder = [
                'task_order' => $i 
             ];                
             Task::where('id',$taskids[$i])->update($taskorder);
        }

        $orderdetail = Task::where('id',$taskids[0])->with('order')->first();
        $orderdate =  date("Y-m-d", strtotime($orderdetail->order->order_time));

        //getting all routes
        $allTasks = Order::whereDate('order_time', $orderdate)->with(['customer', 'task.location', 'agent.team'])->get();
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
                $append['customer_name']         = $tasks->customer->name;
                $append['customer_phone_number'] = $tasks->customer->phone_number;
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
                    if($orderdate != date('Y-m-d'))
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
        $un_order  = Order::whereDate('order_time', $orderdate)->where('auto_alloction','u')->with(['customer', 'task.location'])->get();        
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
               // [driver_detail] => $first_un_loc; $un_route
               $final_un_route['driver_detail'] = $first_un_loc;
               $final_un_route['task_details'] = $un_route;
               $alldrivers[] = $final_un_route;

            }
        }
        
        // echo "<pre>";
        // print_r($alldrivers); die;
        $output = array();
        $output['allroutedata'] = $alldrivers;            
        echo json_encode($output);   
            
        
    }

    

}
