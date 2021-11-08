<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Model\{Agent, AgentPayment, Order, Task};

class DriverTransactionController extends BaseController
{
    public function transactionDetails(Request $request, $id)
    {
        $data = [];
        $agent = Agent::where('id', $id)->first();

        $cash  = 0;
        $order = 0;
        $driver_cost = 0;
        $credit = 0;
        $debit = 0;
        $tasks = [];
        $payments = [];
        $final_balance = 0.00;
        $totalCashCollected = 0;

        if ($agent) {
            $page = $request->has('page') ? $request->page : 1;
            $limit = $request->has('limit') ? $request->limit : 50;
            $payments = AgentPayment::where("driver_id", $id)->get();
            $cash  = $agent->order->sum('cash_to_be_collected');
            $driver_cost  = $agent->order->sum('driver_cost');
            $order_cost = $agent->order->sum('order_cost');
            $credit = $agent->agentPayment->sum('cr');
            $debit = $agent->agentPayment->sum('dr');
            $balance = ($debit - $credit) - ($cash - $driver_cost);
            $final_balance = number_format($balance, 2, '.', '');

            if(!empty($request->from_date) && !empty($request->to_date)){
                $orders = Order::where('driver_id', $id)->whereBetween('order_time', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"])->pluck('id')->toArray();
            }else{
                $orders = Order::where('driver_id', $id)->pluck('id')->toArray();
            }
            if (isset($orders)) {
                $tasks = Task::whereIn('order_id', $orders)->whereIn('task_status', [4,5])
                ->with(['location','tasktype','order.customer'])
                ->select('id','order_id','dependent_task_id','task_type_id','location_id','appointment_duration','task_status','allocation_type','created_at','barcode')
                ->orderBy('order_id', 'DESC')
                ->get();
    
                $totalCashCollected = 0;
                foreach($tasks as $task){
                    if(!empty($task->order->cash_to_be_collected)){
                        $totalCashCollected += $task->order->cash_to_be_collected;
                    }
                }
            }

            $payments = $tasks->merge($payments)->sortByDesc('created_at')->values()->all();
            $payments = $this->paginate($payments, $limit, $page);
        }
        $data['debit'] = $debit;
        $data['credit'] = $credit;
        $data['order_cost'] = $order_cost;
        $data['driver_cost'] = $driver_cost;
        $data['cash_to_be_collected'] = $cash;
        $data['final_balance'] = $final_balance;
        $data['payments'] = $payments;
        // $data['tasks'] = $tasks;
        $data['totalCashCollected'] = $totalCashCollected;

        return response()->json($data);
    }
}
