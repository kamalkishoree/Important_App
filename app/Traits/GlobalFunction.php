<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Log;
use Carbon\Carbon;
use App\Model\{ChatSocket, Client, Agent, ClientPreference, DriverGeo,Order, PricingRule};
use Illuminate\Support\Facades\Config;


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
            $preference = ClientPreference::select('manage_fleet', 'is_cab_pooling_toggle')->first();
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
            }])->orderBy('id', 'DESC');
            
            if(@$preference->manage_fleet){
                $geoagents = $geoagents->whereHas('agentFleet');
            }
            $geoagents = $geoagents->get()->where("agent_cash_at_hand", '<', $cash_at_hand);

            return $geoagents;

        } catch (\Throwable $th) {
            return [];
        }
    
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

}