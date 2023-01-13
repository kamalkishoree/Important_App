<?php

namespace App\Http\Controllers\Api;

use Log,DB;
use Validator;
use Validation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Model\{Geo,AgentProductPrices,Agent};

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;


class OrderPanelController extends BaseController
{
    public function getProductPrice(Request $request){
        $validator = Validator::make(request()->all(), [
            'product_variant_sku'  => 'required',
            'schedule_date' => 'required',
        ]);

        $myDate = date('Y-m-d',strtotime( $request->schedule_date));
        $start_time = date('H:i',strtotime( $request->start_time));
        // return response()->json([
        //     'data' => $request->product_variant_sku,
        //     'status' => 200,
        //     'message' => __('success'),
        // ], 200);
       // DB::enableQueryLog();
        $agent = Agent::where(['type'=>'Freelancer','is_approved'=>1])
                        ->with(['slots' => function($q) use($myDate,$start_time){
                            $q->whereDate('schedule_date', $myDate);//->where('start_time', '<=', $start_time)->where('end_time', '>=', $start_time);
                            $q->where('booking_type', 'working_hours');
                        },'ProductPrices'=>function ($q) use ($request){
                            $q->where('product_variant_sku',$request->product_variant_sku);
                        }])->whereHas('ProductPrices',function ($q) use ($request){
                            $q->where('product_variant_sku',$request->product_variant_sku);
                        } )->whereHas('slots',function($q) use($myDate,$start_time){
                            $q->whereDate('schedule_date', $myDate);//->where('start_time', '<=', $start_time)->where('end_time', '>=', $start_time);
                            $q->where('booking_type', 'working_hours');
                        })->withCount('completeOrder')->get();
       // pr(DB::getQueryLog());
        
        // pr( $agent->toArray());
        // $agent = AgentProductPrices::with('agent','agent.slots')->whereHas('agent.slots', function($q) use($myDate,$start_time){
        //     $q->whereDate('schedule_date', $myDate)->where('start_time', '<=', $start_time)->where('end_time', '>=', $start_time);
        // })->whereHas('agent', function($q){
        //     $q->where(['type'=>'Freelancer','is_approved'=>1]);
        // })->where('product_variant_sku',$request->product_variant_sku)->get();
        $imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
        $agents=[];
        $commonSlot=[];
        foreach( $agent as $productPrice){
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