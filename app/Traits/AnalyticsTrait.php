<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Log;
use Carbon\Carbon;
use App\Model\{Client,Order};
use Illuminate\Support\Facades\Config;


trait AnalyticsTrait{
    
    /**
     * get Analytics data by type like this_day,prev_day,this_week,prev_week,this_month,prev_month.
     */
    public function AgentOrderAnalytics($data,$type){
        if($data){
            $order_assigned = $order_unassigned  = $order_completed = $total_order = $order_live = $order_failed =  0;
            $statusArr = [];
            foreach($data as $order){

                if($order->status == 'assigned'){
                    $live_orders         = $this->AnalyticsOrderStatus($order->id,'live_order');
                    $assigned_orders     = $this->AnalyticsOrderStatus($order->id,'assigned');
                    if($live_orders->count() > 0){
                        $order_live  += 1;
                        $total_order += 1;
                    }
                    if($assigned_orders->count() > 0){
                        $order_assigned  += 1;
                        $total_order     += 1; 
                    }
                }else if($order->status == 'unassigned'){
                    $order_unassigned += 1;
                    $total_order      += 1;
                }
                else if($order->status == 'completed'){
                    $order_completed += 1;
                    $total_order     += 1;
                }
                else if($order->status == 'failed' || $order->status == 'cancelled'){
                    $order_failed    += 1;
                    $total_order     += 1;
                }
            }
           $statusArr = ['assigned'=>$order_assigned,'unassigned'=>$order_unassigned,'order_live'=>$order_live,'order_failed'=>$order_failed,'completed'=>$order_completed,'total_order'=>$total_order,$type=>$data->count()];
           return json_encode($statusArr,true);
        }
    }

    /**
     * percentage Analytics data
    */
    public function PercentageAgentAnalytics($data1,$data2){
        $data1                  = json_decode($data1,true);
        $data2                  = json_decode($data2,true);
        if(isset($data1['this_day']) && isset($data2['prev_day'])){
            $result_assigned = $result_complete = $result_order_amount = $result_unassigned = $result_live_order = $result_fail_order = array();

            $this_assigned              = $data1['assigned'];
            $prev_assigned              = $data2['assigned'];

            $this_unassigned            = $data1['unassigned'];
            $prev_unassigned            = $data2['unassigned'];

            $this_completed             = $data1['completed'];
            $prev_completed             = $data2['completed'];

            $this_live_order            = $data1['order_live'];
            $prev_live_order            = $data2['order_live'];

            $this_failed_order          = $data1['order_failed'];
            $prev_failed_order          = $data2['order_failed'];

            $this_total_order          = $data1['total_order'];
            $prev_total_order          = $data2['total_order'];


            if($this_assigned >= 0 && $prev_assigned >= 0){
                $percentage   			        = $this_assigned - $prev_assigned;
                $assigned_pecentage_this_day 	= ($percentage / 100) * 100;
                $assigned_pecentage_prev_day 	= ($prev_assigned / 100) * 100;
                $result_assigned                = ['assigned_pecentage_this_day'=>$assigned_pecentage_this_day,'assigned_pecentage_prev_day'=>$assigned_pecentage_prev_day];
            }
            
            if($this_total_order >= 0 && $prev_total_order >= 0){
                $percentage   			            = $this_total_order - $prev_total_order;
                $total_order_pecentage_this_day 	= ($percentage / 100) * 100;
                $total_order_pecentage_prev_day 	= ($prev_total_order / 100) * 100;
                $result_order_amount                = ['total_order_pecentage_this_day'=>$total_order_pecentage_this_day,'total_order_pecentage_prev_day'=>$total_order_pecentage_prev_day];
            }
            if($this_unassigned >= 0 && $prev_unassigned >= 0){
                $percentage   			        = $prev_unassigned - $prev_unassigned;
                $unassigned_pecentage_this_day 	= ($percentage / 100) * 100;
                $unassigned_pecentage_prev_day 	= ($prev_unassigned / 100) * 100;
                $result_unassigned              = ['unassigned_pecentage_this_day'=>$unassigned_pecentage_this_day,'unassigned_pecentage_prev_day'=>$unassigned_pecentage_prev_day];
            }

            if($this_live_order >= 0 && $prev_live_order >= 0){
                $percentage   			        = $this_live_order - $prev_live_order;
                $live_order_pecentage_this_day 	= ($percentage / 100) * 100;
                $live_order_pecentage_prev_day 	= ($prev_assigned / 100) * 100;
                $result_live_order                = ['live_order_pecentage_this_day'=>$live_order_pecentage_this_day,'live_order_pecentage_prev_day'=>$live_order_pecentage_prev_day];
            }

            if($this_failed_order >= 0 && $prev_failed_order >= 0){
                $percentage   			            = $this_failed_order - $prev_failed_order;
                $failed_order_pecentage_this_day 	= ($percentage / 100) * 100;
                $failed_order_pecentage_prev_day 	= ($prev_assigned / 100) * 100;
                $result_fail_order                    = ['failed_order_pecentage_this_day'=>$failed_order_pecentage_this_day,'failed_order_pecentage_prev_day'=>$failed_order_pecentage_prev_day];
            }

            if($this_completed >= 0 && $prev_completed >= 0){
                $percentage   			        = $this_completed - $prev_completed;
                $complete_pecentage_this_day 	= ($percentage / 100) * 100;
                $complete_pecentage_prev_day 	= ($prev_completed / 100) * 100;
                $result_complete                = ['complete_pecentage_this_day'=>$complete_pecentage_this_day,'complete_pecentage_prev_day'=>$complete_pecentage_prev_day];
            }

            $result = array_merge($result_assigned,$result_unassigned,$result_live_order,$result_fail_order,$result_complete,$result_order_amount);
        }
      
        if(isset($data1['this_week']) && isset($data2['prev_week'])){
            $result_assigned = $result_complete = $result_order_amount = $result_unassigned = array();

            $this_assigned              = $data1['assigned'];
            $prev_assigned              = $data2['assigned'];

            $this_unassigned            = $data1['unassigned'];
            $prev_unassigned            = $data2['unassigned'];

            $this_completed             = $data1['completed'];
            $prev_completed             = $data2['completed'];

            $this_live_order            = $data1['order_live'];
            $prev_live_order            = $data2['order_live'];

            $this_failed_order          = $data1['order_failed'];
            $prev_failed_order          = $data2['order_failed'];

            $this_total_order          = $data1['total_order'];
            $prev_total_order          = $data2['total_order'];


            if($this_assigned >= 0 && $prev_assigned >= 0){
                $percentage   			        = $this_assigned - $prev_assigned;
                $assigned_pecentage_this_week 	= ($percentage / 100) * 100;
                $assigned_pecentage_prev_week 	= ($prev_assigned / 100) * 100;
                $result_assigned                = ['assigned_pecentage_this_week'=>$assigned_pecentage_this_week,'assigned_pecentage_prev_week'=>$assigned_pecentage_prev_week];
            }
            
            if($this_total_order >= 0 && $prev_total_order >= 0){
                $percentage   			            = $this_total_order - $prev_total_order;
                $total_order_pecentage_this_week 	= ($percentage / 100) * 100;
                $total_order_pecentage_prev_week 	= ($prev_total_order / 100) * 100;
                $result_order_amount                = ['total_order_pecentage_this_week'=>$total_order_pecentage_this_week,'total_order_pecentage_prev_week'=>$total_order_pecentage_prev_week];
            }
            if($this_unassigned >= 0 && $prev_unassigned >= 0){
                $percentage   			            = $this_unassigned - $prev_unassigned;
                $unassigned_pecentage_this_week 	= ($percentage / 100) * 100;
                $unassigned_pecentage_prev_week 	= ($prev_unassigned / 100) * 100;
                $result_unassigned                  = ['unassigned_pecentage_this_week'=>$unassigned_pecentage_this_week,'unassigned_pecentage_prev_week'=>$unassigned_pecentage_prev_week];
            }

            if($this_live_order >= 0 && $prev_live_order >= 0){
                $percentage   			            = $this_live_order - $prev_live_order;
                $live_order_pecentage_this_week 	= ($percentage / 100) * 100;
                $live_order_pecentage_prev_week 	= ($prev_assigned / 100) * 100;
                $result_live_order                    = ['live_order_pecentage_this_week'=>$live_order_pecentage_this_week,'live_order_pecentage_prev_week'=>$live_order_pecentage_prev_week];
            }
            
            if($this_failed_order >= 0 && $prev_failed_order >= 0){
                $percentage   			            = $this_failed_order - $prev_failed_order;
                $failed_order_pecentage_this_week 	= ($percentage / 100) * 100;
                $failed_order_pecentage_prev_week 	= ($prev_assigned / 100) * 100;
                $result_fail_order                  = ['failed_order_pecentage_this_week'=>$failed_order_pecentage_this_week,'failed_order_pecentage_prev_week'=>$failed_order_pecentage_prev_week];
            }
            
            if($this_completed >= 0 && $prev_completed >= 0){
                $percentage   			        = $this_completed - $prev_completed;
                $complete_pecentage_this_week 	= ($percentage / 100) * 100;
                $complete_pecentage_prev_week 	= ($prev_completed / 100) * 100;
                $result_complete                = ['complete_pecentage_this_week'=>$complete_pecentage_this_week,'complete_pecentage_prev_week'=>$complete_pecentage_prev_week];
            }

            $result = array_merge($result_assigned,$result_unassigned,$result_live_order,$result_fail_order,$result_complete,$result_order_amount);
        }

        if(isset($data1['this_month']) && isset($data2['prev_month'])){

            $result_assigned = $result_complete = $result_order_amount = $result_unassigned = array();

            $this_assigned              = $data1['assigned'];
            $prev_assigned              = $data2['assigned'];

            $this_unassigned            = $data1['unassigned'];
            $prev_unassigned            = $data2['unassigned'];

            $this_completed             = $data1['completed'];
            $prev_completed             = $data2['completed'];

            $this_live_order            = $data1['order_live'];
            $prev_live_order            = $data2['order_live'];

            $this_failed_order          = $data1['order_failed'];
            $prev_failed_order          = $data2['order_failed'];

            $this_total_order          = $data1['total_order'];
            $prev_total_order          = $data2['total_order'];


            if($this_assigned >= 0 && $prev_assigned >= 0){
                $percentage   			        = $this_assigned - $prev_assigned;
                $assigned_pecentage_this_month 	= ($percentage / 100) * 100;
                $assigned_pecentage_prev_month 	= ($prev_assigned / 100) * 100;
                $result_assigned                = ['assigned_pecentage_this_month'=>$assigned_pecentage_this_month,'assigned_pecentage_prev_month'=>$assigned_pecentage_prev_month];
            }
            
            if($this_total_order >= 0 && $prev_total_order >= 0){
                $percentage   			            = $this_total_order - $prev_total_order;
                $total_order_pecentage_this_month 	= ($percentage / 100) * 100;
                $total_order_pecentage_prev_month 	= ($prev_total_order / 100) * 100;
                $result_order_amount                = ['total_order_pecentage_this_month'=>$total_order_pecentage_this_month,'total_order_pecentage_prev_month'=>$total_order_pecentage_prev_month];
            }
            if($this_unassigned >= 0 && $prev_unassigned >= 0){
                $percentage   			            = $this_unassigned - $prev_unassigned;
                $unassigned_pecentage_this_month 	= ($percentage / 100) * 100;
                $unassigned_pecentage_prev_month 	= ($prev_unassigned / 100) * 100;
                $result_unassigned                  = ['unassigned_pecentage_this_month'=>$unassigned_pecentage_this_month,'unassigned_pecentage_prev_month'=>$unassigned_pecentage_prev_month];
            }
            if($this_live_order >= 0 && $prev_live_order >= 0){
                $percentage   			            = $this_live_order - $prev_live_order;
                $live_order_pecentage_this_month 	= ($percentage / 100) * 100;
                $live_order_pecentage_prev_month 	= ($prev_assigned / 100) * 100;
                $result_live_order                  = ['live_order_pecentage_this_month'=>$live_order_pecentage_this_month,'live_order_pecentage_prev_month'=>$live_order_pecentage_prev_month];
            }
            
            if($this_failed_order >= 0 && $prev_failed_order >= 0){
                $percentage   			            = $this_failed_order - $prev_failed_order;
                $failed_order_pecentage_this_month 	= ($percentage / 100) * 100;
                $failed_order_pecentage_prev_month 	= ($prev_assigned / 100) * 100;
                $result_fail_order                  = ['failed_order_pecentage_this_month'=>$failed_order_pecentage_this_month,'failed_order_pecentage_prev_month'=>$failed_order_pecentage_prev_month];
            }

            if($this_completed >= 0 && $prev_completed >= 0){
                $percentage   			        = $this_completed - $prev_completed;
                $complete_pecentage_this_month 	= ($percentage / 100) * 100;
                $complete_pecentage_prev_month 	= ($prev_completed / 100) * 100;
                $result_complete                = ['complete_pecentage_this_month'=>$complete_pecentage_this_month,'complete_pecentage_prev_month'=>$complete_pecentage_prev_month];
            }

            $result = array_merge($result_assigned,$result_unassigned,$result_live_order,$result_fail_order,$result_complete,$result_order_amount);
        }
       
        return $result;
    }

     /**
     * Analytics Order status like Assigned/live order
    */
    public function AnalyticsOrderStatus($order_id,$type){

        if($type == 'live_order'){
            $live_order = Order::whereHas('task', function($q){
                $q->whereIn('task_status',[2,3,4]);
            })->where('id',$order_id)->get();
            $result = $live_order;
        }else if($type == 'assigned'){
            $assigned_orders = Order::whereHas('task', function($q){
                $q->whereIn('task_status',[1]);
            })->where('id',$order_id)->get();
            $result = $assigned_orders;
        }
       
        return $result;
    }

    /**
     * get all analytics order record or get all analytics by order agent-(driver id)
     */
    public function AnalyticsOrders($agent_id = ''){
        if(!empty($agent_id)){
            $agent_id         =  $agent_id;
            $yesterday        =  date("Y-m-d", strtotime( '-1 days' ) );
            $this_day         =  Order::where('driver_id',$agent_id)->whereDate('order_time',Carbon::now()->toDateString())->get();
            $prev_day         =  Order::where('driver_id',$agent_id)->whereDate('order_time', $yesterday)->get();
            $this_week        =  Order::where('driver_id',$agent_id)->whereBetween('order_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            $prev_week        =  Order::where('driver_id',$agent_id)->whereBetween('order_time', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
            $this_month       =  Order::where('driver_id',$agent_id)->whereMonth('order_time',Carbon::now()->month)->get();
            $prev_month       =  Order::where('driver_id',$agent_id)->whereMonth( 'order_time', '=', Carbon::now()->subMonth()->month)->get();
        }else{
             // Get all orders
            $yesterday        =  date("Y-m-d", strtotime( '-1 days' ) );
            $this_day         =  Order::whereDate('order_time',Carbon::now()->toDateString())->get();
            $prev_day         =  Order::whereDate('order_time', $yesterday)->get();
            $this_week        =  Order::whereBetween('order_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            $prev_week        =  Order::whereBetween('order_time', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
            $this_month       =  Order::whereMonth('order_time',Carbon::now()->month)->get();
            $prev_month       =  Order::whereMonth( 'order_time', '=', Carbon::now()->subMonth()->month)->get();
        }

        if($this_day){
            $this_day    =  $this->AgentOrderAnalytics($this_day,'this_day');
         }
         if($prev_day){
           $prev_day     =  $this->AgentOrderAnalytics($prev_day,'prev_day');
         }

         /*** Percentage this day and prev day */

         if(!empty($this_day) && !empty($prev_day)){
             $percentage                                 = $this->PercentageAgentAnalytics($this_day,$prev_day);
             $arr                                        = json_decode($this_day, TRUE);
             $arr1                                       = json_decode($prev_day, TRUE);

             $arr['assigned_pecentage_this_day']         = $percentage['assigned_pecentage_this_day'];
             $arr['unassigned_pecentage_this_day']       = $percentage['unassigned_pecentage_this_day'];
             $arr['live_order_pecentage_this_day']       = $percentage['live_order_pecentage_this_day'];
             $arr['failed_order_pecentage_this_day']     = $percentage['failed_order_pecentage_this_day'];
             $arr['complete_pecentage_this_day']         = $percentage['complete_pecentage_this_day'];
             $arr['total_order_pecentage_this_day']      = $percentage['total_order_pecentage_this_day'];
             $this_day                                   = json_encode($arr);

             $arr1['assigned_pecentage_prev_day']        = $percentage['assigned_pecentage_prev_day'];
             $arr1['unassigned_pecentage_prev_day']      = $percentage['unassigned_pecentage_prev_day'];
             $arr1['live_order_pecentage_prev_day']      = $percentage['live_order_pecentage_prev_day'];
             $arr1['failed_order_pecentage_prev_day']    = $percentage['failed_order_pecentage_prev_day'];
             $arr1['complete_pecentage_prev_day']        = $percentage['complete_pecentage_prev_day'];
             $arr1['total_order_pecentage_prev_day']     = $percentage['total_order_pecentage_prev_day'];
             $prev_day                                   = json_encode($arr1);
         }

         if($this_week){
             $this_week  =  $this->AgentOrderAnalytics($this_week,'this_week');
         }
         if($prev_week){
             $prev_week  = $this->AgentOrderAnalytics($prev_week,'prev_week');
         }

          /*** Percentage this week and prev week */

         if(!empty($this_week) && !empty($prev_week)){
             $percentage                                = $this->PercentageAgentAnalytics($this_week,$prev_week);
             $arr                                       = json_decode($this_week, TRUE);
             $arr1                                      = json_decode($prev_week, TRUE);
             $arr['assigned_pecentage_this_week']       = $percentage['assigned_pecentage_this_week'];
             $arr['unassigned_pecentage_this_week']     = $percentage['unassigned_pecentage_this_week'];
             $arr['live_order_pecentage_this_week']     = $percentage['live_order_pecentage_this_week'];
             $arr['failed_order_pecentage_this_week']   = $percentage['failed_order_pecentage_this_week'];
             $arr['complete_pecentage_this_week']       = $percentage['complete_pecentage_this_week'];
             $arr['total_order_pecentage_this_week']    = $percentage['total_order_pecentage_this_week'];
             $this_week                                 = json_encode($arr);

             $arr1['assigned_pecentage_prev_week']      = $percentage['assigned_pecentage_prev_week'];
             $arr1['unassigned_pecentage_prev_week']    = $percentage['unassigned_pecentage_prev_week'];
             $arr1['live_order_pecentage_prev_week']    = $percentage['live_order_pecentage_prev_week'];
             $arr1['failed_order_pecentage_prev_week']  = $percentage['failed_order_pecentage_prev_week'];
             $arr1['complete_pecentage_prev_week']      = $percentage['complete_pecentage_prev_week'];
             $arr1['total_order_pecentage_prev_week']   = $percentage['total_order_pecentage_prev_week'];
             $prev_week                                 = json_encode($arr1);
             
          }

         
         if($this_month){
             $this_month =  $this->AgentOrderAnalytics($this_month,'this_month');
         }
         if($prev_month){
             $prev_month =  $this->AgentOrderAnalytics($prev_month,'prev_month');
         }


         /*** Percentage this month  and prev month */

         if(!empty($this_month) && !empty($prev_month)){
             $percentage                                 =  $this->PercentageAgentAnalytics($this_month,$prev_month);
             $arr                                        = json_decode($this_month, TRUE);
             $arr1                                       = json_decode($prev_month, TRUE);
             $arr['assigned_pecentage_this_month']       = $percentage['assigned_pecentage_this_month'];
             $arr['unassigned_pecentage_this_month']     = $percentage['unassigned_pecentage_this_month'];
             $arr['live_order_pecentage_this_month']     = $percentage['live_order_pecentage_this_month'];
             $arr['failed_order_pecentage_this_month']   = $percentage['failed_order_pecentage_this_month'];
             $arr['complete_pecentage_this_month']       = $percentage['complete_pecentage_this_month'];
             $arr['total_order_pecentage_this_month']    = $percentage['total_order_pecentage_this_month'];
             $this_month                                 = json_encode($arr);

             $arr1['assigned_pecentage_prev_month']      = $percentage['assigned_pecentage_prev_month'];
             $arr1['unassigned_pecentage_prev_month']    = $percentage['unassigned_pecentage_prev_month'];
             $arr1['live_order_pecentage_prev_month']    = $percentage['live_order_pecentage_prev_month'];
             $arr1['failed_order_pecentage_prev_month']  = $percentage['failed_order_pecentage_prev_month'];
             $arr1['complete_pecentage_prev_month']      = $percentage['complete_pecentage_prev_month'];
             $arr1['total_order_pecentage_prev_month']   = $percentage['total_order_pecentage_prev_month'];
             $prev_month                                 = json_encode($arr1);
          }
         $order_analytics   =  ['this_day'=>$this_day,'prev_day'=>$prev_day,'this_week'=>$this_week,'prev_week'=>$prev_week,'this_month'=>$this_month,'prev_month'=>$prev_month];
         return $order_analytics;

    }
   
}