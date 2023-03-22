<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Log;
use App\Model\{ChatSocket, Client, Agent, ClientPreference, DriverGeo,Order,Task,OrderAdditionData, PricingRule, DriverHomeAddress, Location};
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Stmt\Else_;


trait GlobalFunction{



    public static function socketDropDown()
    {
        $chatSocket= ChatSocket::where('status', 1)->get();
        return $chatSocket;
    }

    public static function checkDbStat($id)
    {
        try {
            $client = Client::find($id);

            $schemaName = 'db_' . $client->database_name;
            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');

            $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => $database_host,
            'port' => $database_port,
            'database' => $schemaName,
            'username' => $database_username,
            'password' => $database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            $return['schemaName'] =  $schemaName;
            $return['clientData'] =  $client;

            return $return;
        } catch (\Throwable $th) {
            $return['schemaName'] =  '';
            $return['clientData'] =  [];
            return $return;
        }

    }


    public function getGeoBasedAgentsData($geo, $is_cab_pooling, $agent_tag = '', $date, $cash_at_hand)
    {
        try {
            $preference = ClientPreference::select('manage_fleet', 'is_cab_pooling_toggle', 'is_threshold')->first();
            $geoagents_ids =  DriverGeo::where('geo_id', $geo);

            if($preference->is_cab_pooling_toggle == 1 && $is_cab_pooling == 1){
                $geoagents_ids = $geoagents_ids->whereHas('agent', function($q) use ($geo, $is_cab_pooling){
                    $q->where('is_pooling_available', $is_cab_pooling);
                });
            }

            if($agent_tag !='')
            {
                $geoagents_ids = $geoagents_ids->whereHas('agent.tags', function($q) use ($agent_tag){
                    $q->where('name', '=', $agent_tag);
                });
            }

            $geoagents_ids =  $geoagents_ids->pluck('driver_id');


            $geoagents = Agent::whereIn('id',  $geoagents_ids)->with(['logs','order'=> function ($f) use ($date) {
                $f->whereDate('order_time', $date)->with('task');
            }]);

            if(@$preference->is_threshold == 1){
                $geoagents = $geoagents->where('is_threshold', 1);
            }

            $geoagents = $geoagents->orderBy('id', 'DESC');

            if(@$preference->manage_fleet){
                $geoagents = $geoagents->whereHas('agentFleet');
            }
            $geoagents = $geoagents->get()->where("agent_cash_at_hand", '<', $cash_at_hand);
            return $geoagents;

        } catch (\Throwable $th) {
            return [];
        }

    }
    public function getDriverTaskDonePercentage($agent_id)
    {
        $orders = Order::where('driver_id', $agent_id)->pluck('id')->toArray();
        $CompletedTasks = Task::whereIn('order_id', $orders)
                                ->where(function($q) {
                                    $q->where('task_status',4 );
                                })->count();
        $totalTask = Task::whereIn('order_id', $orders)
                                ->where(function($q) {
                                    $q->whereIn('task_status',[5,4] ) 
                                    ->orWhereHas('order', function($q1){
                                        $q1->where('status', 'cancelled');
                                    });
                                })->count();
        $average =0;
        if( $CompletedTasks > 0){
            $average  = (  $CompletedTasks * 100) /$totalTask;        
        }         
        $data['averageRating'] = number_format($average,2);
        $data['CompletedTasks'] = $CompletedTasks;
        $data['totalTask'] =  $totalTask;
        return  $data;
    }

    //---------function to get pricing rule based on agent_tag/geo fence/timetable/day/time
    public function getPricingRuleData($geoid, $agent_tag = '', $order_datetime = '')
    {
        try {

            if($geoid!='' && $agent_tag!='' && $order_datetime != '')
            {

                $dayname = Carbon::parse($order_datetime)->format('l');
                $time    = Carbon::parse($order_datetime)->format('H:i');

                $pricingRule = PricingRule::orderBy('id', 'desc')->whereHas('priceRuleTags.tagsForAgent',function($q)use($agent_tag){
                                    $q->where('name', $agent_tag);
                                })->whereHas('priceRuleTags.geoFence',function($q)use($geoid){
                                    $q->where('id', $geoid);
                                })->where('apply_timetable', '=', 1)
                                ->whereHas('priceRuleTimeframe', function($query) use ($dayname, $time){
                                    $query->where('is_applicable', 1)
                                        ->Where('day_name', '=', $dayname)
                                        ->whereTime('start_time', '<=', $time)
                                        ->whereTime('end_time', '>=', $time);
                                })->first();

                if(empty($pricingRule)){
                    $pricingRule = PricingRule::orderBy('id', 'desc')->whereHas('priceRuleTags.tagsForAgent',function($q)use($agent_tag){
                        $q->where('name', $agent_tag);
                    })->whereHas('priceRuleTags.geoFence',function($q)use($geoid){
                        $q->where('id', $geoid);
                    })->where('apply_timetable', '!=', 1)->first();
                }
                
            }

            if(empty($pricingRule)){
                $pricingRule = PricingRule::where('is_default', 1)->first();
            }

            return $pricingRule;

        } catch (\Throwable $th) {
            return [];
        }
    
    }


    public function getConvertUTCToLocalTime($datetime, $localtimezone = 'UTC')
    {
        $local_datetime = Carbon::parse($datetime, 'UTC')->setTimezone($localtimezone)->format('Y-m-d H:i:s');
        return $local_datetime;
    }


    public function updateOrderAdditional($request=[],$order_id)
    {
        $requestOnly = ['category_name','specific_instruction'];
        $validated_keys = $request->only($requestOnly);
       
        $order_id = @$order_id;
    
        foreach($validated_keys as $key => $value){
          OrderAdditionData::updateOrCreate(
                ['key_name' => $key, 'order_id' => $order_id],
                ['key_name' => $key, 'key_value' => $value,'order_id' => $order_id]);
        }
        return 1;
        
    }


    
    /**
     * Check agent go to home address Distance enable or disabled
     */
    public function CheckAgentHomeAddress($finalLocation,$id)
    {
        try {
            $checkHomeaddress   =  Agent::where(['id'=>$id,'is_go_to_home_address'=>1])->count();
            $max_distance       =  5;
            if($checkHomeaddress > 0){
                $address = DriverHomeAddress::where(['agent_id'=>$id,'is_default'=>1])->first();
                if(!empty($address)){
                    $latitude       =   $address->latitude;
                    $longitude      =   $address->longitude;
                    $distance       =   $this->DistanceAgentHomeAddess($finalLocation->latitude,$finalLocation->longitude,$latitude,$longitude);
                    if($distance <= $max_distance){
                        return true;
                    }else{
                        return false;
                    }
                    
                }else{
                    $location =  $this->lastAgentDropoffLocation($id);
                   
                    if(isset($location) && !empty($location)){
                        $location   = $location->location;
                        $latitude   = $location->latitude;
                        $longitude  = $location->longitude;
                        $distance   = $this->DistanceAgentHomeAddess($finalLocation->latitude,$finalLocation->longitude,$latitude,$longitude);
                        
                        if($distance <= $max_distance){
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
            }
        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
    }


    /**
     *  Distance between go to home address and customer final location
     */
    public function DistanceAgentHomeAddess($client_lat,$client_long,$agent_home_addres_lat,$agent_home_addres_long){
        $client = ClientPreference::where('id', 1)->first();
        $value = [];
        $send   = [];
        $ch = curl_init();
            $headers = array('Accept: application/json',
                    'Content-Type: application/json',
                    );
            $url =  'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$client_lat.','.$client_long.'&destinations='.$agent_home_addres_lat.','.$agent_home_addres_long.'&key='.$client->map_key_1.'';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch); // Close the connection
            $new =   $result;
            if(count($result->rows) > 0){
                array_push($value, $result->rows[0]->elements);
            }
            if (isset($value)) {
                $totalDistance = 0;
                $totalDuration = 0;
                foreach ($value as $i => $item) {
                    //dd($item);
                    $totalDistance = $totalDistance + (isset($item[$i]->distance) ? $item[$i]->distance->value : 0);
                    $totalDuration = $totalDuration + (isset($item[$i]->duration) ? $item[$i]->duration->value : 0);
                }
    
    
                if ($client->distance_unit == 'metric') {
                    $send['distance'] = round($totalDistance/1000, 2);      //km
                } else {
                    $send['distance'] = round($totalDistance/1609.34, 2);  //mile
                }
                //
                $newvalue = round($totalDuration/60, 2);
                $whole = floor($newvalue);
                $fraction = $newvalue - $whole;
    
                if ($fraction >= 0.60) {
                    $send['duration'] = $whole + 1;
                } else {
                    $send['duration'] = $whole;
                }
            }
            return $send;
            
    }
   
   /**
     *  Distance between go to home address and agent last frop off location
     */
    public function lastAgentDropoffLocation($id){
        $count = Order::where('driver_id', $id)->where('status', 'completed')->orderBy('id', 'desc')->count();
        if($count > 0){
            $order = Order::where('driver_id', $id)->where('status', 'completed')->orderBy('id', 'desc')->first();
            $drop_off = task::where('task_type_id',2)->where('order_id',$order->id)->with('location')->first();
            if(isset($drop_off) && !empty($drop_off)){
                return $drop_off;
            }
        }
    }
}


