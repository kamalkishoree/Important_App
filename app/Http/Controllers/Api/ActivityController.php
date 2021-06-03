<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{User, Agent, AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof,Timezone};
use Validation;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Model\Roster;
use Config;
use Illuminate\Support\Facades\URL;

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
        $header = $request->header();
        $client_code = Client::where('database_name',$header['client'][0])->first();
        $tz = new Timezone();
        $client_code->timezone = $tz->timezone_name($client_code->timezone);
        $start     = Carbon::now($client_code->timezone ?? 'UTC')->startOfDay();
        $end       = Carbon::now($client_code->timezone ?? 'UTC')->endOfDay();
        $utc_start = Carbon::parse($start . $client_code->timezone ?? 'UTC')->tz('UTC');
        $utc_end   = Carbon::parse($end . $client_code->timezone ?? 'UTC')->tz('UTC');
        
        $id     = Auth::user()->id;

        $all    = $request->all; 
        $tasks   = [];
        
        if($all == 1){
            $orders = Order::where('driver_id',$id)->where('status','assigned')->orderBy('order_time')->pluck('id')->toArray();
            
        }else{
            $orders = Order::where('driver_id',$id)->where('order_time','>=',$utc_start)->where('order_time','<=',$utc_end)->where('status','assigned')->orderBy('order_time')->pluck('id')->toArray();
        }
       

        if (count($orders) > 0) {
            $tasks = Task::whereIn('order_id',$orders)->where('task_status','!=',4)->Where('task_status','!=',5)->with(['location','tasktype','order.customer'])->orderBy('order_id', 'DESC')
            ->get();
            if(count($tasks) > 0)
            {
                //sort according to task_order
                $tasks = $tasks->toArray();            
                if($tasks[0]['task_order'] !=0)
                {                
                    usort($tasks, function($a, $b) {
                        return $a['task_order'] <=> $b['task_order'];
                    });
                }
            }
            
            
        }
        dd(url('/task'));
   
        return response()->json([
            'data' => $tasks,
        ],200);
        
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

        $header = $request->header();
        $client_code = Client::where('database_name',$header['client'][0])->first();
        $tz = new Timezone();
        $client_code->timezone = $tz->timezone_name($client_code->timezone);
        $start     = Carbon::now($client_code->timezone ?? 'UTC')->startOfDay();
        $end       = Carbon::now($client_code->timezone ?? 'UTC')->endOfDay();
        $utc_start = Carbon::parse($start . $client_code->timezone ?? 'UTC')->tz('UTC');
        $utc_end   = Carbon::parse($end . $client_code->timezone ?? 'UTC')->tz('UTC');
        
        $tasks   = [];
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

        if($request->lat=="" || $request->lat==0 || $request->lat== '0.00000000')
        {

        }else{
            AgentLog::create($data);
        }

           
        

        $id    = Auth::user()->id;
        $all   = $request->all; 

        if($all == 1){
            $orders = Order::where('driver_id',$id)->where('status','assigned')->orderBy('order_time')->pluck('id')->toArray();
            
        }else{
            $orders = Order::where('driver_id',$id)->where('order_time','>=',$utc_start)->where('order_time','<=',$utc_end)->where('status','assigned')->orderBy('order_time')->pluck('id')->toArray();
        }
        
       
        if (count($orders) > 0) {
            $tasks = Task::whereIn('order_id',$orders)->where('task_status','!=',4)->Where('task_status','!=',5)->with(['location','tasktype','order.customer'])->orderBy('order_id', 'DESC')
            ->get();
            if(count($tasks) > 0)
            {
                    //sort according to task_order
                    $tasks = $tasks->toArray();            
                    if($tasks[0]['task_order'] !=0)
                    {                
                        usort($tasks, function($a, $b) {
                            return $a['task_order'] <=> $b['task_order'];
                        });
                    }
            }            
        }
        
        $agents     = Agent::where('id',$id)->with('team')->first();
        $taskProof = TaskProof::all();

        $prefer    = ClientPreference::select('theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'date_format', 'time_format', 'map_type','map_key_1')->first();
        $allcation = AllocationRule::first('request_expiry');

        $prefer['alert_dismiss_time'] = (int)$allcation->request_expiry;
        $agents['client_preference']  = $prefer;
        $agents['task_proof']         = $taskProof;
        $datas['user']                = $agents;
        $datas['tasks']               = $tasks;

        return response()->json([
            'data' => $datas,
        ],200);
    }

    public function cmsData(Request $request)
    {
       
        $data = Cms::where('id',$request->cms_id)->first();

        return response()->json([
            'data' => $data,
           ],200);
    }


    public function taskHistory()
    {
        $id    = Auth::user()->id;
       
        $orders = Order::where('driver_id',$id)->pluck('id')->toArray();
        if (isset($orders)) {
            $tasks = Task::whereIn('order_id',$orders)->whereIn('task_status',[4,5])->with(['location','tasktype','order.customer'])->orderBy('order_id','DESC')
             ->get(['id','order_id','dependent_task_id','task_type_id','location_id','appointment_duration','task_status','allocation_type','created_at','barcode']);
        }else{
            $task = [];
        }

        return response()->json([
            'data' => $tasks,
        ],200);
            
    }
    
  
}
