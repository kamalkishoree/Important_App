<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\Customer;
use App\Model\Location;
use App\Model\Order;
use App\Model\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\AnalyticsTrait;
use App\Traits\GlobalFunction;


class AccountingController extends Controller
{
    use GlobalFunction;
    use AnalyticsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $complete_order_analytics = '';
        $order_analytic_data = [];
        if ($request->has('date')) {
            $date_array =  (explode(" to ", $request->date));
            $dateform = Carbon::parse($date_array[0])->startOfDay();
            $dateto   = Carbon::parse(isset($date_array[1]) ? $date_array[1]:$date_array[0])->endOfDay();
        } else {
            $dateform = \Carbon\Carbon::today()->startOfDay();
            $dateto   = \Carbon\Carbon::today()->endOfDay();
            $order_analytic_data = $this->AnalyticsOrders();
        }


        $counter            = 0;
        $totalearning       = Order::whereBetween('order_time', [$dateform,$dateto])->sum('order_cost');
        $totalagentearning  = Order::whereBetween('order_time', [$dateform,$dateto])->sum('driver_cost');
        $totalorders        = Order::whereBetween('order_time', [$dateform,$dateto])->count();
        $totalagents        = Agent::count();

        $agents             = Agent::orderBy('cash_at_hand', 'DESC')->get();
        $customers          = Customer::withCount('orders')->orderBy('orders_count', 'DESC')->get();
        $heatLatLog         = Location::whereIn('id', function ($query) use ($dateform, $dateto) {
            $query->select('location_id')
                              ->from(with(new Task)->getTable())
                              ->whereBetween('created_at', [$dateform,$dateto]);
        })->get();

        //print_r($heatLatLog); die;
        if ($request->has('type')) {
            $type = $request->type;
        } else {
            $type = 3;
        }

        switch ($type) {
            case 1:     // for today

                    $dates[]    = date("d M Y");
                    $serchdate  = date("Y-m-d");

                    $countOrders[]  = Order::whereDate('order_time', $serchdate)->count();
                    $sumOrders[]    = Order::whereDate('order_time', $serchdate)->sum('order_cost');

                        $display        = date('d M Y', strtotime('-1 day', strtotime($serchdate)));
                        $check          = date('Y-m-d', strtotime('-1 day', strtotime($serchdate)));
                        $lastcount      = 0;
                        $lastsum        = 0;


                break;
            case 2:     // for weekly


                $date = \Carbon\Carbon::today();

                $ts = strtotime($date);

                $year = date('o', $ts);
                $week = date('W', $ts);

                for ($i = 1; $i <= 7; $i++) {
                    $ts = strtotime($year.'W'.$week.$i);
                    $dates[]    = date("d M Y", $ts);
                    $serchdate  = date("Y-m-d", $ts);
                    $countOrders[]  = Order::whereDate('order_time', $serchdate)->count();
                    $sumOrders[]    = Order::whereDate('order_time', $serchdate)->sum('order_cost');

                    if ($i == 1) {
                        $display        = date('d M Y', strtotime('-1 day', strtotime($serchdate)));
                        $check          = date('Y-m-d', strtotime('-1 day', strtotime($serchdate)));
                        $lastcount      = Order::whereDate('order_time', $check)->count();
                        $lastsum        = Order::whereDate('order_time', $check)->sum('order_cost');
                        array_unshift($countOrders, $lastcount);
                        array_unshift($sumOrders, $lastsum);
                    }
                }


            break;

            default:     // for monthly

                for ($i = 1; $i <=  date('t'); $i++) {
                    $counter++;
                    // add the date to the dates array
                    $dates[]        = str_pad($i, 2, '0', STR_PAD_LEFT) . " " . date('M') . " " . date('Y');
                    $serchdate      = date('Y')."-" . date('m') . "-" .str_pad($i, 2, '0', STR_PAD_LEFT);
                    $countOrders[]  = Order::whereDate('order_time', $serchdate)->count();
                    $sumOrders[]    = Order::whereDate('order_time', $serchdate)->sum('order_cost');

                    if ($i == 1) {
                        $display        = date('d M Y', strtotime('-1 day', strtotime($serchdate)));
                        $check          = date('Y-m-d', strtotime('-1 day', strtotime($serchdate)));
                        $lastcount      = Order::whereDate('order_time', $check)->count();
                        $lastsum        = Order::whereDate('order_time', $check)->sum('order_cost');


                        array_unshift($countOrders, $lastcount);
                        array_unshift($sumOrders, $lastsum);
                    }
                }
        }

        $startDate = date('Y-m-d',strtotime($dateform));
        $endDate = date('Y-m-d',strtotime($dateto));
        return view('accounting', compact('totalearning', 'totalagentearning', 'totalorders', 'totalagents', 'agents', 'customers', 'heatLatLog', 'countOrders', 'sumOrders', 'dates', 'type','order_analytic_data','startDate','endDate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get order analytics data by agent
     */
    public function getAgentOrderAnalytics(Request $request){
        $order_analytics = $this->AnalyticsOrders($request->agent_id);
        return json_encode($order_analytics);
    }

    public function viewAgentOrderAnalytics(Request $request){
        $agent_id           = $request->agent_id;
        $data_type          = $request->data_type;
        $data_status        = $request->data_status;
        $yesterday          = date("Y-m-d", strtotime( '-1 days' ) );

         if($data_type == 'this_day'){
            if($data_status == 'live'){
                $orders             =  Order::whereHas('task', function($q){
                                        $q->whereIn('task_status',[2,3,4]);
                                    })->with('customer','agent')->where(['status'=>'assigned'])->whereDate('order_time',Carbon::now()->toDateString())->get();
                if($agent_id){
                    $orders         =  Order::whereHas('task', function($q){
                        $q->whereIn('task_status',[2,3,4]);
                    })->with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>'assigned'])->whereDate('order_time',Carbon::now()->toDateString())->get();
                }
            }else{
                $orders             =  Order::with('customer','agent')->where(['status'=>$data_status])->whereDate('order_time',Carbon::now()->toDateString())->get();
                if($agent_id){
                    $orders         =  Order::with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>$data_status])->whereDate('order_time',Carbon::now()->toDateString())->get();
                }
            }

        }elseif($data_type == 'prev_day'){
            if($data_status == 'live'){
                $orders             =  Order::whereHas('task', function($q){
                                            $q->whereIn('task_status',[2,3,4]);
                                        })->with('customer','agent')->where(['status'=>'assigned'])->whereDate('order_time', $yesterday)->get();
                if($agent_id){
                    $orders         =  Order::whereHas('task', function($q){
                                            $q->whereIn('task_status',[2,3,4]);
                                        })->with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>'assigned'])->whereDate('order_time', $yesterday)->get();
                }
            }else{
                $orders             =  Order::with('customer','agent')->where(['status'=>$data_status])->whereDate('order_time', $yesterday)->get();
                if($agent_id){
                    $orders         =  Order::with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>$data_status])->whereDate('order_time', $yesterday)->get();
                }
            }

        }
        elseif($data_type == 'this_week'){
            if($data_status == 'live'){
                $orders             =  Order::whereHas('task', function($q){
                                            $q->whereIn('task_status',[2,3,4]);
                                        })->with('customer','agent')->where(['status'=>'assigned'])->whereBetween('order_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                if($agent_id){
                    $orders         =  Order::whereHas('task', function($q){
                                        $q->whereIn('task_status',[2,3,4]);
                                        })->with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>'assigned'])->whereBetween('order_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                }
            }else{
                $orders             =  Order::with('customer','agent')->where(['status'=>$data_status])->whereBetween('order_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                if($agent_id){
                    $orders         =  Order::with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>$data_status])->whereBetween('order_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                }
            }

        }
        elseif($data_type == 'prev_week'){
            if($data_status == 'live'){
                $orders             =  Order::whereHas('task', function($q){
                                        $q->whereIn('task_status',[2,3,4]);
                                    })->with('customer','agent')->where(['status'=>'assigned'])->whereBetween('order_time', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
                if($agent_id){
                    $orders         =  Order::whereHas('task', function($q){
                                        $q->whereIn('task_status',[2,3,4]);
                                    })->with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>'assigned'])->whereBetween('order_time', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
                }
            }else{
                $orders             =  Order::with('customer','agent')->where(['status'=>$data_status])->whereBetween('order_time', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
                if($agent_id){
                    $orders         =  Order::with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>$data_status])->whereBetween('order_time', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
                }
            }
        }
        elseif($data_type == 'this_month'){
            if($data_status == 'live'){
                $orders             =  Order::whereHas('task', function($q){
                                            $q->whereIn('task_status',[2,3,4]);
                                        })->with('customer','agent')->where(['status'=>'assigned'])->whereMonth('order_time',Carbon::now()->month)->get();
                if($agent_id){
                    $orders         =   Order::whereHas('task', function($q){
                                            $q->whereIn('task_status',[2,3,4]);
                                        })->with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>'assigned'])->whereMonth('order_time',Carbon::now()->month)->get();
                }
            }else{
                $orders             =  Order::with('customer','agent')->where(['status'=>$data_status])->whereMonth('order_time',Carbon::now()->month)->get();
                if($agent_id){
                    $orders         =  Order::with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>$data_status])->whereMonth('order_time',Carbon::now()->month)->get();
                }
            }

        }
        elseif($data_type == 'prev_month'){

            if($data_status == 'live'){
                $orders             =  Order::whereHas('task', function($q){
                                        $q->whereIn('task_status',[2,3,4]);
                                    })->with('customer','agent')->where(['status'=>'assigned'])->whereMonth( 'order_time', '=', Carbon::now()->subMonth()->month)->get();

                if($agent_id){
                    $orders         =  Order::whereHas('task', function($q){
                        $q->whereIn('task_status',[2,3,4]);
                    })->with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>'assigned'])->whereMonth( 'order_time', '=', Carbon::now()->subMonth()->month)->get();
                }
            }else{
                $orders             =  Order::with('customer','agent')->where(['status'=>$data_status])->whereMonth( 'order_time', '=', Carbon::now()->subMonth()->month)->get();
                if($agent_id){
                    $orders         =  Order::with('customer','agent')->where(['driver_id'=>$agent_id,'status'=>$data_status])->whereMonth( 'order_time', '=', Carbon::now()->subMonth()->month)->get();
                }
            }

        }

        if($orders){
            return view('modal.modalViewAnalytics', compact('orders','data_status'));
        }
    }
}
