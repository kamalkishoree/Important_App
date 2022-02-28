<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use Mail;
use Config;
use Validation;
use Carbon\Carbon;
use Kawankoding\Fcm\Fcm;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use GuzzleHttp\Client as GClient;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Model\{Agent, Client, Customer, Geo, Location, Order, Roster, Task, TaskReject, Timezone, AllocationRule, ClientPreference, DriverGeo, NotificationEvent, NotificationType, SmtpDetail, PricingRule, TagsForAgent, TagsForTeam, Team, TaskProof, OrderCancelReason};

class OrderController extends BaseController
{
    use ApiResponser;

    public function createOrderCancelRequest(Request $request, $id){
        try{
            $user = Auth::user();
            $order = Order::where('id', $id)->where('status', '!=', 'completed')->where('driver_id', $user->id)->first();
            if(!$order){
                return $this->error(__('Invalid Data'), 422);
            }

            $dispatch_order_cancel_url = str_replace('/dispatch-pickup-delivery/', '/dispatch-order-cancel-request/', $order->call_back_url);
            
            $client = new GClient(['content-type' => 'application/json']);
            $res = $client->get($dispatch_order_cancel_url.'?reject_reason='.urlencode($request->reject_reason));
            $response = json_decode($res->getBody(), true);
            
            if($response['status'] == 'Success'){
                return $this->success('', $response['message']);
            }else{
                return $this->error($response['message'], 422);
            }
        }
        catch(\Exception $ex){
            return $this->error(__('Server Error'), $ex->getCode());
        }
    }

    public function getOrderCancelReasons(Request $request){
        $reasons = OrderCancelReason::where('status', 1)->get();
        return $this->success($reasons);
    }
}
