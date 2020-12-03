<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{User, Agent, Client, ClientPreference, Order, Task};
use Validation;
use DB;

class ActivityController extends BaseController
{

	/**
     * update driver availability status if 0 than 1 if 1 than 0

     */
    public function updateDriverStatus(Request $request)
    {
        $agent = Agent::findOrFail(Auth::user()->id); 
        $agent->is_available = ($agent->is_available == 1) ? 0 : 1;
        $agent->update();

        return response()->json([
            'message' => 'Status updated Successfully',
            'data' => array('is_available' => $agent->is_available)
        ]);

    }

    /**
     * Login user and create token
     *
     */
    public function tasks(Request $request)
    {
        $tasks = Task::with('location', 'tasktype', 'pricing')
                        ->select('tasks.*', 'orders.recipient_phone', 'orders.Recipient_email', 'orders.task_description', 'customers.phone_number  as customer_mobile', 'customers.email  as customer_email', 'customers.name as customer_name')
                        ->join('orders', 'orders.id' , 'tasks.order_id')
                        ->join('customers', 'customers.id' , 'orders.customer_id');
        if(!empty($request->date)){
            $date = date('Y-m-d', strtotime($request->date));
            $tasks = $tasks->whereDate('tasks.created_at', $date);
        }
        $tasks = $tasks->where('orders.driver_id', Auth::user()->id)->paginate();
        return response()->json($tasks);
        
    }

    /**
     * Login user and create token
     *

     */
    public function profile(Request $request)
    {
        
    }
  
}
