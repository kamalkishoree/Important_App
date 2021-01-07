<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{User, Agent, AgentLog, Client, ClientPreference, Cms, Order, Task};
use Validation;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Model\Roster;
use Config;

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
        $tasks = Task::where('task_status',1)->orWhere('task_status',2)->with('location', 'tasktype', 'pricing')
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
       
       $agent = Agent::where('id',Auth::user()->id)->first();

       return response()->json([
        'data' => $agent,
       ],200);

    }

    public function updateProfile(Request $request)
    {
        $saved = Agent::where('id',Auth::user()->id)->first();
        
        $header = $request->header();
        $client_code = Client::where('database_name',$header['client'][0])->first('code');
        $getFileName = '';
        // Handle File Upload
        if(isset($request->profile_picture)){

            if ($request->hasFile('profile_picture')) {
                $folder = str_pad($client_code->code, 8, '0', STR_PAD_LEFT);
                $folder = 'client_'.$folder;
                $file = $request->file('profile_picture');
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                $s3filePath = '/assets/'.$folder.'/agents' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file,'public');
                $getFileName = $path;
            }

        }else{
            $getFileName = $saved->profile_picture;
        }
        
        
        $agent                   = Agent::find(Auth::user()->id);
        $agent->name             = isset($request->name)?$request->name:$saved->name;
        $agent->profile_picture  = $getFileName;
        $agent->vehicle_type_id  = $request->vehicle_type_id;
        $agent->make_model       = isset($request->make_model)?$request->make_model:$saved->make_model;
        $agent->plate_number     = isset($request->plate_number)?$request->plate_number:$saved->plate_number;
        $agent->phone_number     = isset($request->phone_number)?$request->phone_number:$saved->phone_number;
        $agent->color            = isset($request->color)?$request->color:$saved->color;

        if($agent->save()){
            return response()->json([
                'message' => 'Profile Updated Successfully',
            ],200);
        } else {
            return response()->json([
                'message' => 'Sorry Something Went Wrong',
            ],404);
        }


    }



    public function agentLog(Request $request)
    {
        
       
        $agent = AgentLog::where('agent_id',Auth::user()->id)->first();
        
        $data =  [
            'agent_id'          => Auth::user()->id,
            'lat'               => $request->lat,
            'long'              => $request->long,
            'battery_level'     => $request->battery_level,
            'os_version'        => $request->os_version,
            'app_version'       => $request->app_version,
            'current_speed'     => $request->current_speed,
            'on_route'          => $request->on_route,
            'device_type'       => $request->device_type
        ];

           
        AgentLog::create($data);
        
        return response()->json([
            'data' => 'Log Saved Successfully',
        ],200);
    }

    public function cmsData(Request $request)
    {
       
        $data = Cms::where('id',$request->cms_id)->first();

        return response()->json([
            'data' => $data,
           ],200);
    }
    
  
}
