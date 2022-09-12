<?php

namespace App\Http\Controllers\Api;

use DB;
use Config;
use Validator;
use Carbon\Carbon;
use App\Model\Roster;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GClient;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Agent, AgentSlot,AgentSlotRoster,AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof, Timezone, User, DriverGeo, Geo, TagsForAgent};

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
                $StartTime = $agent->slots->first() ? $agent->slots->first()->start_time : '';
                $EndTime   = $agent->slots->first() ? $agent->slots->first()->end_time : '';
                $Duration  = $request->service_time ?? 60;
                $slots =  $this->SplitTime($myDate, $StartTime, $EndTime, $Duration, $delayMin = 0);
                pr($slots);
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
    public function SplitTime($myDate, $StartTime, $EndTime, $Duration="60", $delayMin = 0)
    {
        $Duration = (($Duration==0)?'60':$Duration);

        $timezoneset = 'Asia/Kolkata';
        $cr = Carbon::now()->addMinutes($delayMin);
      
      
        $nowT = strtotime($cr);
        $nowA = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$StartTime);
        $nowS = Carbon::createFromFormat('Y-m-d H:i:s', $nowA)->timestamp;
        $nowE = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$EndTime)->timestamp;
        if ($nowT > $nowE) {
            return [];
        } elseif ($nowT>$nowS) {
            $StartTime = date('H:i', strtotime($now));
        } else {
            $StartTime = date('H:i', strtotime($nowA));
        }
    
        $ReturnArray = array();
        $StartTime = strtotime($StartTime); //Get Timestamp
        $EndTime = strtotime($EndTime); //Get Timestamp
        $AddMins = $Duration * 60;
        $endtm = 0;
    
        while ($StartTime <= $EndTime) {
            $endtm = $StartTime + $AddMins;
            if ($endtm>$EndTime) {
                $endtm = $EndTime;
            }
            $ReturnArray[] = date("G:i", $StartTime).' - '.date("G:i", $endtm);
            $StartTime += $AddMins;
            $endtm = 0;
        }
        return $ReturnArray;
    }

       
   
}
