<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{Agent, AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof, Timezone, User, DriverGeo, Geo, TagsForAgent};
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Model\Roster;
use Config;
use Illuminate\Support\Facades\URL;
use GuzzleHttp\Client as GClient;
use App\Http\Controllers\Api\AgentController;

class AgentSlotController extends BaseController
{
    /**   get agent according to lat long  */
    function getAgentsSlotByTags(Request $request){
        try {

            $validator = Validator::make(request()->all(), [
                'latitude'  => 'required',
                'longitude' => 'required',
                'tags'      => 'required',
                'schedule_date' => 'required',
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $agentController = new AgentController();
            $geoid = $this->findLocalityByLatLng($request->latitude, $request->longitude);
            $geoagents_ids = DriverGeo::where('geo_id', $geoid)->pluck('driver_id');

            $tagId = '';
            if(!empty($request->tag)){
                $tag = TagsForAgent::where('name', $request->tag)->get()->first();
                if(!empty($tag)){
                    $tagId = $tag->id;
                }else{
                    return response()->json([
                        'data' => [],
                        'status' => 200,
                        'message' => __('Agents not found.')
                    ], 200);
                }

            }

            $geoagents = Agent::with(['agentlog','vehicle_type']);

            if(!empty($tagId)){
                $geoagents->whereHas('tags', function($q) use($tagId){
                    $q->where('tag_id', $tagId);
                });
            }

            $geoagents = $geoagents->whereIn('id', $geoagents_ids)->where(["is_available" => 1, "is_approved" => 1])->orderBy('id', 'DESC')->get();
            // $preferences = ClientPreference::with('currency')->first();
            // $clientPreference = json_decode($preferences->customer_notification_per_distance);
            
            $finalAgents = [];
            foreach($geoagents as $geoagent){
                $agentLat = $geoagent->agentlog->lat;
                $agentLong = $geoagent->agentlog->long;

                $getDistance = $this->getLatLongDistance($agentLat, $agentLong, $request->latitude, $request->longitude, 'km');
                
                //get agent under 5 km
                if($getDistance < 6){
                    $geoagent->distance = $getDistance;
                    $geoagent->distance_type = 'km';
                    $finalAgents[] = $geoagent;
                }

                $getArrivalTime = $this->getLatLongDistance($agentLat, $agentLong, $request->latitude, $request->longitude, 'minutes');
                $geoagent->arrival_time = $getArrivalTime;
                
            }

            $distance = array_column($finalAgents, 'distance');
            array_multisort($distance, SORT_ASC, $finalAgents);

            return response()->json([
                'data' => $finalAgents,
                'status' => 200,
                'message' => __('success')
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }

        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
            if ((($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]))) {
                $c = !$c;
            }
        }
        return $c;
   
}
