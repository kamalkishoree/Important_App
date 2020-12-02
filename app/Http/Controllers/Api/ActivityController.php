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
        $tasks = Task::with('location', 'tasktype', 'pricing');
        if(!empty($request->date)){
            $date = date('Y-m-d', strtotime($request->date));
            $tasks = $tasks->whereDate('created_at', $date);
        }
        $tasks = $tasks->get();
        return response()->json([
            'message' => 'Status updated Successfully',
            'data' => $tasks
        ]);
        
    }

    /**
     * Login user and create token
     *

     */
    public function profile(Request $request)
    {
        
    }
  
}
