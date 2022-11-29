<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\AgentLog;
use App\Model\ClientPreference;
use App\Model\Order;
use App\Model\Task;
use App\Model\Team;
use App\Model\LocationDistance;
use App\Model\Client;
use App\Model\Timezone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Countries;
use App\Traits\googleMapApiFunctions;
use Log;
class AgentDashBoardController extends Controller
{
    use googleMapApiFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // This function is for sending silent push notification
    public function sendsilentnotification($notification_data)
    {
        $new = [];
        array_push($new, $notification_data['device_token']);
        if (isset($new)) {
            fcm()
            ->to($new) // $recipients must an array
            ->data($notification_data)
            ->notification([
                'sound' =>  'default',
            ])
            ->send();
            Log::info('sendsilentnotification');
        }
    }

    //function to load latest order/route and agent data with or without html
    public function dashboardTeamData(Request $request)
    {
        $userstatus = isset($request->userstatus)?$request->userstatus:2;
        $team_id = isset($request->team_id)?$request->team_id:'';
        $is_load_html = isset($request->is_load_html)?$request->is_load_html:1;
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

        //setting timezone from id
        $tz = new Timezone();
        $auth->timezone = $tz->timezone_name(Auth::user()->timezone);

        if(isset($request->routedate)) {
            $date = Carbon::parse(strtotime($request->routedate))->format('Y-m-d');
        }else{
            $date = date('Y-m-d');
        }
        $startdate = date("Y-m-d 00:00:00", strtotime($date));
        $enddate = date("Y-m-d 23:59:59", strtotime($date));


        $startdate = Carbon::parse($startdate . @$auth->timezone ?? 'UTC')->tz('UTC');
        $enddate = Carbon::parse($enddate . @$auth->timezone ?? 'UTC')->tz('UTC');

        //left side bar list for display all teams
        if($userstatus!=2):
            $teams  = Team::with(
                [ 
                    'agents' => function ($query) use ($userstatus, $startdate, $enddate) {
                        $query->where('is_available', '=', $userstatus)
                            ->with(['agentlog', 
                                'order'  => function ($q) use ($startdate, $enddate){
                                $q->where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with(['customer', 'task.location']);
                                }
                            ]
                        );
                    },
                ]
            );
        else:
            $teams  = Team::with(
                [
                    'agents.order' => function ($o) use ($startdate, $enddate) {
                        $o->where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->with(['customer', 'task.location']);
                    },
                ]
            );
        endif;
        
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }

        if(!empty($team_id)){
            $teams = $teams->where('id', $team_id);
        }

        $teams = $teams->get();

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
        $unassigned = Agent::where('team_id', null)->with(['order' => function ($o) use ($startdate, $enddate) {
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
                $append['team_id']               = isset($tasks->driver_id) ? @$tasks->agent->team_id : 0;
                $append['driver_name']           = isset($tasks->driver_id) ? @$tasks->agent->name : '';
                $append['driver_id']             = isset($tasks->driver_id) ? $tasks->driver_id : '';
                $append['customer_name']         = isset($tasks->customer->name)?$tasks->customer->name:'';
                $append['customer_phone_number'] = isset($tasks->customer->phone_number)?$tasks->customer->phone_number:'';
                $append['task_order']            = isset($task->task_order)?$task->task_order:0;
                array_push($newmarker, $append);
            }
        }

        $unassigned->toArray();
        $teams->toArray();

        $agents = Agent::with('agentlog');
        if($userstatus!=2):
            $agents->where('is_available', $userstatus);
        endif;
        
        $agents = $agents->get()->toArray();

        $preference  = ClientPreference::where('id', 1)->first(['theme','date_format','time_format']);

        $uniquedrivers = array();
        $j = 0;
        foreach ($agents as $singleagent) {
            if(empty($singleagent['agentlog'])){
                $singleagent['agentlog']['id'] = null;
                $singleagent['agentlog']['agent_id'] = $singleagent['id'];
                $singleagent['agentlog']['current_task_id'] = null;
                $singleagent['agentlog']['lat'] = null;
                $singleagent['agentlog']['long'] = null;
                $singleagent['agentlog']['battery_level'] = null;
                $singleagent['agentlog']['os_version'] = null;
                $singleagent['agentlog']['app_version'] = null;
                $singleagent['agentlog']['current_speed'] = null;
                $singleagent['agentlog']['on_route '] = null;
                $singleagent['agentlog']['app_version'] = null;
            }
            if (is_array($singleagent['agentlog'])) {
                $taskarray = array();
                foreach ($newmarker as $singlemark) {
                    if ($singlemark['driver_id'] == $singleagent['agentlog']['agent_id']) {
                        $taskarray[] = $singlemark;
                    }
                }
                if (!empty($taskarray)) {
                    usort($taskarray, function ($a, $b) {
                        return $a['task_order'] <=> $b['task_order'];
                    });
                    if ($date != date('Y-m-d')) {
                        $singleagent['agentlog']['lat'] = $taskarray[0]['latitude'];
                        $singleagent['agentlog']['long'] = $taskarray[0]['longitude'];
                    }
                    $uniquedrivers[$j]['driver_detail'] = $singleagent['agentlog'];
                    $uniquedrivers[$j]['task_details'] = $taskarray;
                    $j++;
                }
            }else{

            }
        }

        //for route optimization
        $routeoptimization = array();
        $taskarray = array();
        foreach ($uniquedrivers as $singledriver) {
            if (count($singledriver['task_details'])>1) {
                $points = array();
                $points[] = array(floatval($singledriver['driver_detail']['lat']),floatval($singledriver['driver_detail']['long']));
                $taskids = array();
                foreach ($singledriver['task_details'] as $singletask) {
                    $points[] = array(floatval($singletask['latitude']),floatval($singletask['longitude']));
                    $taskids[] = $singletask['task_id'];
                }

                $taskarray[$singledriver['driver_detail']['agent_id']] = implode(',', $taskids);
                $routeoptimization[$singledriver['driver_detail']['agent_id']] = $points;
            }
        }

        //create distance matrix
        $distancematrix = array();
        foreach ($routeoptimization as $key=>$value) {
            $distancematrix[$key]['tasks'] = $taskarray[$key];
            $distancematrix[$key]['distance'] = $routeoptimization[$key];
        }

        $teamdata = $teams->toArray();

        foreach ($teamdata as $k1=>$singleteam) {
            foreach ($singleteam['agents'] as $k2=>$singleagent) {
                $teamdata[$k1]['agents'][$k2]['taskids']  = [];
                $teamdata[$k1]['agents'][$k2]['total_distance']  = '';
                if (count($singleagent['order'])>0) {
                    //for calculating total distance
                    $sorted_orders = $this->splitOrder($singleagent['order']);
                    if (!empty($sorted_orders)) {
                        $tasklistids = [];
                        foreach ($sorted_orders as $singlesort) {
                            $tasklistids[] = $singlesort['task'][0]['id'];
                        }
                        $teamdata[$k1]['agents'][$k2]['taskids'] = $tasklistids;
                        $driverlocation = [];
                        if ($singleagent['is_available']==1 || $singleagent['is_available']==0) {
                            $singleagentdetail = Agent::where('id', $singleagent['id'])->with('agentlog')->first();
                            $driverlocation['lat'] = $singleagentdetail->agentlog->lat??$singleagentdetail->order[0]['task'][0]['location']['latitude']??'0.000';
                            $driverlocation['long'] = $singleagentdetail->agentlog->long??$singleagentdetail->order[0]['task'][0]['location']['longitude']??'0.000';
                        }
                        $gettotal_distance = $this->getTotalDistance($tasklistids, $driverlocation);
                        $clientPreference  = ClientPreference::where('id', 1)->first();
                        $teamdata[$k1]['agents'][$k2]['total_distance'] = ($clientPreference->distance_unit == 'metric')? $gettotal_distance['total_distance_km'] : $gettotal_distance['total_distance_miles'];
                    }
                    $teamdata[$k1]['agents'][$k2]['order'] = $sorted_orders;
                }
            }
        }

        //unassigned_orders
        $unassigned_orders = array();
        $un_total_distance = '';
        $un_order  = Order::where('order_time', '>=', $startdate)->where('order_time', '<=', $enddate)->where('status', 'unassigned')->with(['customer', 'task.location'])->get();

        if (count($un_order)>=1) {
            $unassigned_orders = $this->splitOrder($un_order->toarray());
            if (count($unassigned_orders)>1) {
                $unassigned_distance_mat = array();
                $unassigned_points = [];
                if(!empty($unassigned_orders[0]['task'][0]['location'])){
                    $unassigned_points[] = array(floatval($unassigned_orders[0]['task'][0]['location']['latitude']),floatval($unassigned_orders[0]['task'][0]['location']['longitude']));
                }
                $unassigned_taskids = array();
                $un_route = array();
                foreach ($unassigned_orders as $singleua) {
                    $unassigned_taskids[] = $singleua['task'][0]['id'];
                    if(!empty($singleua['task'][0]['location'])){
                        // dd($singleua['task'][0]['location']['latitude']);
                        $unassigned_points[] = array(floatval($singleua['task'][0]['location']['latitude']),floatval($singleua['task'][0]['location']['longitude']));
                    }

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
                    $aappend['latitude']              =  $s_task['location']['latitude'] ?? '';
                    $aappend['longitude']             = $s_task['location']['longitude'] ?? '';
                    $aappend['address']               = $s_task['location']['address'] ?? '';
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
                $unassigned_distance_mat['tasks'] = implode(',', $unassigned_taskids);
                $unassigned_distance_mat['distance'] = $unassigned_points;
                $distancematrix[0] = $unassigned_distance_mat;
                $first_un_loc = [];
                if(!empty($unassigned_orders[0]['task'][0]['location'])){
                    $first_un_loc = array('lat'=>floatval($unassigned_orders[0]['task'][0]['location']['latitude']),'long'=>floatval($unassigned_orders[0]['task'][0]['location']['longitude']));
                }
                $final_un_route['driver_detail'] = $first_un_loc;
                $final_un_route['task_details'] = $un_route;
                $uniquedrivers[] = $final_un_route;

                $gettotal_un_distance = $this->getTotalDistance($unassigned_taskids);

                $un_total_distance = $gettotal_un_distance['total_distance_miles'];
            }
        }

        $client = ClientPreference::where('id', 1)->first();

        $googleapikey = $client->map_key_1??'';

        $getAdminCurrentCountry = Countries::where('id', '=', Auth::user()->country_id)->get()->first();
        if(!empty($getAdminCurrentCountry)){
            $defaultCountryLatitude  = $getAdminCurrentCountry->latitude;
            $defaultCountryLongitude  = $getAdminCurrentCountry->longitude;
        }else{
            $defaultCountryLatitude  = '';
            $defaultCountryLongitude  = '';
        }

        $data = array('status' =>"success", 'teams' => $teamdata, 'userstatus' => $userstatus, 'client_code' => Auth::user()->code, 'defaultCountryLongitude' => $defaultCountryLongitude, 'defaultCountryLatitude' => $defaultCountryLatitude, 'newmarker' => $newmarker, 'unassigned' => $unassigned, 'agents' => $agents,'date'=> $date,'preference' =>$preference, 'routedata' => $uniquedrivers,'distance_matrix' => $distancematrix, 'unassigned_orders' => $unassigned_orders,'unassigned_distance' => $un_total_distance, 'map_key'=>$googleapikey, 'client_timezone'=>$auth->timezone);
        
        // $data = array('agents' => $agents);

        if($is_load_html == 1)
        {
            return view('agent_dashboard_task_html')->with($data)->render();
        }else{
            return json_encode($data);
        }
    }

}
