<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\BaseController;
use App\Model\Order;
use App\Model\Roster;
use App\Model\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validation;

class TaskController extends BaseController
{
    
    public function updateTaskStatus(Request $request)
    { 
        if(isset($request->note)){
            $note = $request->note;
        }else{
            $note = '';
        }
        
        $task = Order::where('id',$request->task_id)->update(['task_status' => $request->task_status,'note'=> $note]);
    }
}
