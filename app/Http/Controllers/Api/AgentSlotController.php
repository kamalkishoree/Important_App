<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{Agent, AgentSlot,AgentSlotRoster,AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof, Timezone, User, DriverGeo, Geo, TagsForAgent};
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
       
        //pr($request->all());
        // try {

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
            $geoid = $agentController->findLocalityByLatLng($request->latitude, $request->longitude);
            $geoagents_ids = DriverGeo::where('geo_id', $geoid)->pluck('driver_id');
          //  pr($geoagents_ids);
            $tagId = '';
            if(!empty($request->tags)){
                $tag = TagsForAgent::where('name', $request->tags)->get()->first();
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
           
            $tagId = '';
            $myDate = Carbon::createFromFormat('Y-m-d', $request->schedule_date);
            
            $geoagents = Agent::with(['agentlog','vehicle_type','slots' => function($q) use($myDate){
                $q->whereDate('schedule_date', $myDate);
            }]);

            if(!empty($tagId)){
                $geoagents->whereHas('tags', function($q) use($tagId){
                    $q->where('tag_id', $tagId);
                });
            }
         
            if($request->schedule_date){
                $geoagents->whereHas('slots', function($q) use($myDate){
                    $q->whereDate('schedule_date', $myDate);
                });
            }
            

            $geoagents = $geoagents->whereIn('id', $geoagents_ids)->where(["is_available" => 1, "is_approved" => 1])->orderBy('id', 'DESC')->get();
       
            foreach( $geoagents as $agent){
               pr( $agent->slots->first());
            }
            
            pr( $geoagents->toArray());

            return response()->json([
                'data' => $geoagents,
                'status' => 200,
                'message' => __('success')
            ], 200);

        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => $e->getMessage()
        //     ], 400);
        // }

    }

       
   
}
