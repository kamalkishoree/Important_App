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
use App\Model\{Agent, Client, Customer, Geo, Location, Order, OrderCancelRequest, Roster, Task, TaskReject, Timezone, AllocationRule, ClientPreference, DriverGeo, NotificationEvent, NotificationType, SmtpDetail, PricingRule, TagsForAgent, TagsForTeam, Team, TaskProof};

class OrderController extends BaseController
{
    use ApiResponser;

    public function createOrderCancelRequest(Request $request, $id){
        try{
            $user = Auth::user();
            $order = Order::with('cancel_request')->where('id', $id)->where('status', '!=', 'completed')->where('driver_id', $user->id)->first();
            if(!$order){
                return $this->error(__('Invalid Data'), 422);
            }
            if($order->cancel_request && $order->cancel_request->status == 0){
                return $this->error(__('Cancel request has already been submitted'), 422);
            }
            if($order->cancel_request && $order->cancel_request->status == 1){
                return $this->error(__('Cancel request has already been processed'), 422);
            }

            $order_cancel_request = new OrderCancelRequest();
            $order_cancel_request->order_id = $id;
            $order_cancel_request->driver_id = $user->id;
            $order_cancel_request->reject_reason = $request->reject_reason;
            $order_cancel_request->status = 0;
            $order_cancel_request->save();

            return $this->success('', __('Your request for order cancellation has been submitted'));
        }
        catch(\Exception $ex){
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }
}
