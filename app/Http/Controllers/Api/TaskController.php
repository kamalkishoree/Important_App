<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\BaseController;
use App\Model\Order;
use App\Model\Roster;
use App\Model\Task;
use App\Model\TaskReject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\Count;
use Validation;

class TaskController extends BaseController
{
    
    public function updateTaskStatus(Request $request)
    { 
        $note = '';
        if(isset($request->note)) {
            $note = $request->note;
        } else {
            $note = '';
        }


        $orderAll   = Task::where('id',$request->task_id)->get();
        $orderId    = Task::where('id',$request->task_id)->first('order_id');
        $allCount   = Count($orderAll);
        $inProgress = $orderAll->where('task_status',2);
        $lasttask   = count($orderAll->where('task_status',3));
        $check      = $allCount - $lasttask;
        if($request->task_id == 3) {
            if($check == 1){
                $Order  = Order::where('id',$orderId)->update(['status' => $request->task_status,'note'=> $note]);
            }
        } else {
            $Order  = Order::where('id',$orderId)->update(['status' => $request->task_status,'note'=> $note]);
        }
        
        $task = Task::where('id',$request->task_id)->update(['task_status' => $request->task_status]);
        $newDetails = Task::where('id',$request->task_id)->first();
       
        return response()->json([
            'data' => $newDetails,
        ]);

    }

    public function TaskUpdateReject(Request $request)
    {
          //die($request->order_id);
        if($request->status == 1){

            Order::where('id',$request->order_id)->update(['driver_id' => $request->driver_id,'status'=>'assigned']);

            return response()->json([
                'message' => 'Task Accecpted Successfully',
            ],200);

        } else {
           
            $data = [
                'order_id'          => $request->order_id,
                'driver_id'         => $request->driver_id,
                'status'            => $request->status,
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString(),
            ];
            TaskReject::create($data);

            return response()->json([
                'message' => 'Task Rejected Successfully',
            ],200);

        }
        
        
    }

}

