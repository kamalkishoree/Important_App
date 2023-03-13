<?php

namespace App\Http\Controllers\Api;

use Log,DB;
use Validator;
use Validation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Model\{Geo,AgentProductPrices,Agent,DriverGeo,AgentSlotRoster};

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\AgentController;
use App\Traits\{GlobalFunction};

class OrderPanelController extends BaseController
{
    use GlobalFunction;

    public function getProductPrice(Request $request){
      
        
        $validator = Validator::make(request()->all(), [
            'product_variant_sku'  => 'required',
            'schedule_date' => 'required',
        ]);

        $myDate = date('Y-m-d',strtotime( $request->schedule_date));
        $start_time =  $request->start_time;
        $end_time   = $request->end_time;
        $latitude   = $request->latitude ?? '';
        $longitude  = $request->longitude ?? '';

        $agentController = new AgentController();
        $geoid           = $agentController->findLocalityByLatLng($latitude, $longitude);
      
        
        $geoagents_ids    = DriverGeo::where('geo_id', $geoid)->pluck('driver_id');
   
    // $raw_query = "SELECT `agents`.*, 
    //                     (SELECT COUNT(*) FROM `orders` WHERE `agents`.`id` = `orders`.`driver_id` AND `orders`.`status` = 'complete') AS `complete_order_count`
    //                 FROM `agents`
    //                 WHERE EXISTS (
    //                     SELECT * FROM `agent_slot_rosters` as slots
    //                     WHERE `agents`.`id` = `slots`.`agent_id` 
    //                         AND `slots`.`schedule_date` = '$myDate'
    //                        AND `slots`.`booking_type` != 'blocked'
    //                     ORDER BY `id` DESC
    //                     LIMIT 1
    //                 ) 
    //                     AND `type` = 'Freelancer' 
    //                     AND `is_approved` = 1 
    //                     AND EXISTS (
    //                         SELECT * FROM `agent_product_prices` as `product_prices`
    //                         WHERE `agents`.`id` = `product_prices`.`agent_id`
    //                             AND `product_variant_sku` = '$request->product_variant_sku'
    //                     )
    //                 ";
                  
    //     $slots = DB::select(DB::raw($raw_query));
    //    pr($slots);
        $agent = Agent::whereHas('slots',function($q) use($myDate,$start_time,$end_time){
            $q->whereDate('schedule_date', $myDate)
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>=', $end_time);
            return $q->where('booking_type','!=', 'blocked')->latest();
        })->where(['type'=>'Freelancer','is_approved'=>1])
                        ->with(['ProductPrices'=>function ($q) use ($request){
                            $q->where('product_variant_sku',$request->product_variant_sku);
                        }])->whereHas('ProductPrices',function ($q) use ($request){
                            $q->where('product_variant_sku',$request->product_variant_sku);
                        } )->withCount('completeOrder')
                        ->get();
            // dd(\DB::getQueryLog());
        $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
        $agents=[];
        $commonSlot=[];
        foreach( $agent as $productPrice){
            $averageTaskComplete   = $this->getDriverTaskDonePercentage( $productPrice->id);
        
            $productPrice->averageTaskComplete = $averageTaskComplete['averageRating'];
          //  unset($productPrice->complete_order);
            if($productPrice->agent){
                $productPrice->agent->image_url =  isset($productPrice->agent->profile_picture) ? $imgproxyurl.Storage::disk('s3')->url($productPrice->agent->profile_picture) : Phumbor::url(URL::to('/asset/images/no-image.png'));
            }

        }
        return response()->json([
            'data' => $agent,
            'status' => 200,
            'message' => __('success'),
        ], 200);
       // pr($agent->toArray());

    }
}