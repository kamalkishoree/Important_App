<?php

namespace App\Http\Controllers;

use App\Model\Task;
use App\Model\Location;
use App\Model\Customer;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;
use App\Model\TaskDriverTag;
use App\Model\TaskTeamTag;
use Illuminate\Http\Request;
use App\Model\{Agent, AllocationRule, Client, ClientPreference, DriverGeo, PricingRule, Roster, TaskProof};
use App\Model\Geo;
use App\Model\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\RosterCreate;
use App\Models\RosterDeatil;
use Illuminate\Support\Arr;
use App\Jobs\scheduleNotification;
Use Log;

use GuzzleHttp\Client as Gclient;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        /* Orter status will be done as per task completed. task assigned than assigned all task of order completed tha completed and so on*/

        $tasks = Order::orderBy('created_at', 'DESC')->with(['customer', 'location', 'taskFirst', 'agent', 'task.location']);
        $check = '';
        if ($request->has('status') && $request->status != 'all') 
        {
            $tasks = $tasks->where('status', $request->status);
            $check = $request->status;
        }else{
            $tasks = $tasks->where('status', 'unassigned');
            $check = 'unassigned';
        }
        $all      =  Order::where('status', '!=', null)->get();
        $active   =  count($all->where('status', 'assigned'));
        $pending  =  count($all->where('status', 'unassigned'));
        $history  =  count($all->where('status', 'completed'));
        $failed   =  count($all->where('status','failed'));
        $tasks    =  $tasks->paginate(10);
        $pricingRule = PricingRule::select('id', 'name')->get();
        $teamTag    = TagsForTeam::all();
        $agentTag   = TagsForAgent::all();
        $preference  = ClientPreference::where('id',1)->first(['theme','date_format','time_format']);
        $agents      = Agent::all();
        return view('tasks/task')->with(['tasks' => $tasks, 'status' => $request->status, 'active_count' => $active, 'panding_count' => $pending, 'history_count' => $history, 'status' => $check,'preference' => $preference,'agents'=>$agents,'failed_count'=>$failed]);
    }

    // function for saving new order
    public function newtasks(Request $request)
    {   
        $loc_id = $cus_id = $send_loc_id = $newlat = $newlong = 0;
        $iinputs = $request->toArray();
        $old_address_ids = array();
        foreach($iinputs as $key => $value)
        {
            if(substr_count($key,"old_address_id") == 1)
            {
                $old_address_ids[] = $key;
            }
        }

        $images = [];
        $last = '';
        $customer = [];
        $finalLocation = [];
        $taskcount = 0;
        $latitude  = [];
        $longitude = [];
        $percentage = 0;

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $unique_order_id = substr(str_shuffle(str_repeat($pool, 5)), 0, 6);
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();

        //save task images on s3 bucket

        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
                $s3filePath = '/assets/' . $folder . '/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                array_push($images, $path);

            }
            $last = implode(",", $images);
        }

        //create new customer for task or get id of old customer

        if (!isset($request->ids)) {
            $customer = Customer::where('email', '=', $request->email)->first();
            if (isset($customer->id)) {
                $cus_id = $customer->id;
            } else {
                $cus = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ];
                $customer = Customer::create($cus);
                $cus_id = $customer->id;
            }
        } else {
            $cus_id = $request->ids;
            $customer = Customer::where('id', $request->ids)->first();
        }

        //get pricing rule  for save with every order
        $pricingRule = PricingRule::where('id',1)->first();

        //here order save code is started
        
        $settime = ($request->task_type=="schedule") ? $request->schedule_time : Carbon::now()->toDateTimeString();
        $notification_time = ($request->task_type=="schedule")? Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC') : Carbon::now()->toDateTimeString();
        
        $agent_id          = $request->allocation_type === 'm' ? $request->agent : null;

        $order = [
            'customer_id'                     => $cus_id,
            'recipient_phone'                 => $request->recipient_phone,
            'Recipient_email'                 => $request->recipient_email,
            'task_description'                => $request->task_description,
            'driver_id'                       => $agent_id,
            'auto_alloction'                  => $request->allocation_type,
            'images_array'                    => $last,
            'order_type'                      => $request->task_type,
            'order_time'                      => $notification_time,
            'status'                          => $agent_id != null ? 'assigned' : 'unassigned',
            'cash_to_be_collected'            => $request->cash_to_be_collected,
            'base_price'                      => $pricingRule->base_price,
            'base_duration'                   => $pricingRule->base_duration,
            'base_distance'                   => $pricingRule->base_distance,
            'base_waiting'                    => $pricingRule->base_waiting,
            'duration_price'                  => $pricingRule->duration_price,
            'waiting_price'                   => $pricingRule->waiting_price,
            'distance_fee'                    => $pricingRule->distance_fee,
            'cancel_fee'                      => $pricingRule->cancel_fee,
            'agent_commission_percentage'     => $pricingRule->agent_commission_percentage,
            'agent_commission_fixed'          => $pricingRule->agent_commission_fixed,
            'freelancer_commission_percentage'=> $pricingRule->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $pricingRule->freelancer_commission_fixed,
            'unique_id'                       => $unique_order_id
        ];
        
        $orders = Order::create($order);

        //here is task save code is started

        $dep_id = null; // this is used as dependent task id 
        $pickup_quantity = 0;
        $drop_quantity   = 0;
        foreach ($request->task_type_id as $key => $value) {
            $taskcount++;
            if (isset($request->address[$key])) {
                $loc = [
                    'latitude'    => $request->latitude[$key],
                    'longitude'   => $request->longitude[$key],
                    'short_name'  => $request->short_name[$key],
                    'address'     => $request->address[$key],
                    'post_code'   => $request->post_code[$key],
                    'email'         => $request->address_email[$key],
                    'phone_number'   => $request->address_phone_number[$key],
                    // 'due_after'      => $request->due_after[$key],
                    // 'due_before'    => $request->due_before[$key],
                    'customer_id' => $cus_id,
                ];
                $Loction = Location::create($loc);
                $loc_id = $Loction->id;
                $send_loc_id = $loc_id;               
            } else {
                if ($key == 0) {
                    $loc_id = $request->old_address_id;
                    $send_loc_id = $loc_id;
                } else {                    
                    $loc_id = $request->input($old_address_ids[$key]);
                    $send_loc_id = $loc_id;
                }
            }
            
            $location = Location::where('id', $loc_id)->first();
            if($location->customer_id != $cus_id)
            {
                $newloc = [
                    'latitude'    => $location->latitude,
                    'longitude'   => $location->longitude,
                    'short_name'  => $location->short_name,
                    'address'     => $location->address,
                    'post_code'   => $location->post_code,
                    'email'         => $location->address_email,
                    'phone_number'   => $location->address_phone_number,
                    // 'due_after'      => $location->due_after,
                    // 'due_before'    => $location->due_before,
                    'customer_id' => $cus_id,
                ];
                $location = Location::create($newloc);
            }
            
            $loc_id = $location->id;
            if ($key == 0) {
                $finalLocation = $location;
            }
        
            array_push($latitude,$location->latitude);
            array_push($longitude,$location->longitude);

            $task_appointment_duration = empty($request->appointment_date[$key]) ? '0' : $request->appointment_date[$key];
            $data = [
                'order_id'                   => $orders->id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'appointment_duration'       => $task_appointment_duration,
                'dependent_task_id'          => $dep_id,
                'task_status'                => $agent_id != null ? 1 : 0,
                'created_at'                 => $notification_time,
                'assigned_time'              => $notification_time,
                'barcode'                    => $request->barcode[$key],
                'quantity'                   => $request->quantity[$key]
            ];
            $task = Task::create($data);
            $dep_id = $task->id;

            //for net quantity
            if ($value == 1) {
                $pickup_quantity = $pickup_quantity+$request->quantity[$key];
            } elseif ($value == 2) {
                $drop_quantity   = $drop_quantity+$request->quantity[$key];
            }
            $net_quantity = $pickup_quantity - $drop_quantity;
        }

        //accounting for task duration distanse 
         $getdata = $this->GoogleDistanceMatrix($latitude,$longitude);    
         $paid_duration = $getdata['duration'] - $pricingRule->base_duration;
         $paid_distance = $getdata['distance'] - $pricingRule->base_distance;
         $paid_duration = $paid_duration < 0 ? 0 : $paid_duration;
         $paid_distance = $paid_distance < 0 ? 0 : $paid_distance;
         $total         = $pricingRule->base_price + ($paid_distance * $pricingRule->distance_fee ) + ($paid_duration * $pricingRule->duration_price);

         if(isset($agent_id)){
             $agent_details = Agent::where('id',$agent_id)->first();
            if($agent_details->type == 'Employee'){
                $percentage = $pricingRule->agent_commission_fixed + (($total / 100) * $pricingRule->agent_commission_percentage);
            }else{
                $percentage = $pricingRule->freelancer_commission_percentage + (($total / 100) * $pricingRule->freelancer_commission_fixed);
            }
         }

         //update order with order cost details
         $updateorder = [
            'actual_time'        => $getdata['duration'],
            'actual_distance'    => $getdata['distance'],
            'order_cost'         => $total,
            'driver_cost'        => $percentage,
            'net_quantity'       => $net_quantity

         ];
        
         Order::where('id',$orders->id)->update($updateorder);

        //task tages save code is here
        if (isset($request->allocation_type) && $request->allocation_type === 'a') {
            if (isset($request->team_tag)) {
                $orders->teamtags()->sync($request->team_tag);
            }
            if (isset($request->agent_tag)) {
                $orders->drivertags()->sync($request->agent_tag);
            }
        }

        //this function is called when allocation type is Accept/Reject it find the current task location belongs to which geo fence

        $geo = null;
        if ($request->allocation_type === 'a') {
            $geo = $this->createRoster($send_loc_id);
            $agent_id = null;
        }

        // task schdule code is hare

        $allocation = AllocationRule::where('id', 1)->first();
        if($request->task_type != 'now'){                
            $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();        
            $beforetime = (int)$auth->getAllocation->start_before_task_time;          
            $to = new \DateTime("now", new \DateTimeZone(isset(Auth::user()->timezone)? Auth::user()->timezone : 'Asia/Kolkata') );
            $sendTime = Carbon::now();        
            $to = Carbon::parse($to)->format('Y-m-d H:i:s');
            $from = Carbon::parse($notification_time)->format('Y-m-d H:i:s');        
            $datecheck = 0;
            $to_time = strtotime($to);
            $from_time = strtotime($from);        
            if($to_time >= $from_time) {
                return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
            }

            $diff_in_minutes = round(abs($to_time - $from_time) / 60);

            $schduledata = [];
            if($diff_in_minutes > $beforetime){
                $finaldelay = (int)$diff_in_minutes - $beforetime;
                $time = Carbon::parse($sendTime)
                ->addMinutes($finaldelay)
                ->format('Y-m-d H:i:s');
                $schduledata['geo']               = $geo;
                //$schduledata['notification_time'] = $time;
                $schduledata['notification_time'] = $notification_time;                    
                $schduledata['agent_id']          = $agent_id;
                $schduledata['orders_id']         = $orders->id;
                $schduledata['customer']          = $customer;
                $schduledata['finalLocation']     = $finalLocation;
                $schduledata['taskcount']         = $taskcount;
                $schduledata['allocation']        = $allocation;
                $schduledata['database']          = $auth;                
                scheduleNotification::dispatch($schduledata)->delay(now()->addMinutes($finaldelay));
                return true;
            }
        }        
        
        //this is roster create accounding to the allocation methed
        
        if ($request->allocation_type === 'a' || $request->allocation_type === 'm') {            
            switch ($allocation->auto_assign_logic) {
                case 'one_by_one':
                    //this is called when allocation type is one by one
                    $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                case 'send_to_all':
                    //this is called when allocation type is send to all
                    $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                case 'round_robin':
                    //this is called when allocation type is round robin
                    $this->roundRobin($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                default:
                    //this is called when allocation type is batch wise 
                    $this->batchWise($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
            }
        }
        return true;
    }

    //function for assigning driver to unassigned orders
    public function assignAgent(Request $request)
    {
        $order_update = Order::whereIn('id',$request->orders_id)->update(['driver_id'=>$request->agent_id,'status'=>'assigned']);
        $task         = Task::whereIn('order_id',$request->orders_id)->update(['task_status'=>1]);
        $this->MassAndEditNotification($request->orders_id[0],$request->agent_id);
    }

    //function for updating date of orders
    public function assignDate(Request $request)
    {
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();        
        $notification_time = Carbon::parse($request->newdate . $auth->timezone ?? 'UTC')->tz('UTC');

        $order_update = Order::whereIn('id',$request->orders_id)->update(['order_time'=>$notification_time]);
    }

    //function for sending bulk notification 
    public function MassAndEditNotification($orders_id,$agent_id)
    {
        Log::info('mass and edit notification');
        $order_details = Order::where('id',$orders_id)->with(['customer','agent', 'task.location'])->first();
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $notification_time = $order_details->order_time;
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getPreference->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime); //this function is check the time diffrence and give the notification time
        $randem     = rand(11111111, 99999999);

       
        $allcation_type = 'ACK';
        
        foreach ($order_details->task as $key => $value) {
            $taskcount = count($order_details->task);
            $extraData = [
                'customer_name'            => $order_details->customer->name,
                'customer_phone_number'    => $order_details->customer->phone_number,
                'short_name'               => $value->location->short_name,
                'address'                  => $value->location->address,
                'lat'                      => $value->location->latitude,
                'long'                     => $value->location->longitude,
                'task_count'               => $taskcount,
                'unique_id'                => $randem,
                'created_at'               => Carbon::now()->toDateTimeString(),
                'updated_at'               => Carbon::now()->toDateTimeString(),
            ];
            break;
        }
        
        $oneagent = Agent::where('id', $agent_id)->first();
        $data = [
            'order_id'            => $orders_id,
            'driver_id'           => $agent_id,
            'notification_time'   => $time,
            'type'                => $allcation_type,
            'client_code'         => Auth::user()->code,
            'created_at'          => Carbon::now()->toDateTimeString(),
            'updated_at'          => Carbon::now()->toDateTimeString(),
            'device_type'         => $oneagent->device_type,
            'device_token'        => $oneagent->device_token,
            'detail_id'           => $randem,
        ];
        $this->dispatch(new RosterCreate($data, $extraData)); //this job is for create roster in main database for send the notification  in manual alloction       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamTag     = TagsForTeam::all();
        $agentTag    = TagsForAgent::all();
        $pricingRule = PricingRule::select('id', 'name')->get();
        $allcation   = AllocationRule::where('id', 1)->first();        
        $agents      = Agent::orderBy('created_at', 'DESC')->get();        
        $task_proofs = TaskProof::all(); 
        $returnHTML = view('modals/add-task-modal')->with(['teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'pricingRule' => $pricingRule, 'allcation' => $allcation ,'task_proofs' => $task_proofs ])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $loc_id = $cus_id = $send_loc_id = $newlat = $newlong = 0;
        $images = [];
        $last = '';
        $customer = [];
        $finalLocation = [];
        $taskcount = 0;
        $latitude  = [];
        $longitude = [];
        $percentage = 0;

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $unique_order_id = substr(str_shuffle(str_repeat($pool, 5)), 0, 6);
        
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        //save task images on s3 bucket

        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
                $s3filePath = '/assets/' . $folder . '/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                array_push($images, $path);
            }
            $last = implode(",", $images);
        }

        //create new customer for task or get id of old customer

        if (!isset($request->ids)) {
            $customer = Customer::where('email', '=', $request->email)->first();
            if (isset($customer->id)) {
                $cus_id = $customer->id;
            } else {
                $cus = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ];
                $customer = Customer::create($cus);
                $cus_id = $customer->id;
            }
        } else {
            $cus_id = $request->ids;
            $customer = Customer::where('id', $request->ids)->first();
        }

        //get pricing rule  for save with every order
        $pricingRule = PricingRule::where('id',1)->first();

        //here order save code is started

        $settime = isset($request->schedule_time) ? $request->schedule_time : Carbon::now()->toDateTimeString();
        $notification_time = Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC');
        $agent_id          = $request->allocation_type === 'm' ? $request->agent : null;

        $order = [
            'customer_id'                     => $cus_id,
            'recipient_phone'                 => $request->recipient_phone,
            'Recipient_email'                 => $request->recipient_email,
            'task_description'                => $request->task_description,
            'driver_id'                       => $agent_id,
            'auto_alloction'                  => $request->allocation_type,
            'images_array'                    => $last,
            'order_type'                      => $request->task_type,
            'order_time'                      => $notification_time,
            'status'                          => $agent_id != null ? 'assigned' : 'unassigned',
            'cash_to_be_collected'            => $request->cash_to_be_collected,
            'base_price'                      => $pricingRule->base_price,
            'base_duration'                   => $pricingRule->base_duration,
            'base_distance'                   => $pricingRule->base_distance,
            'base_waiting'                    => $pricingRule->base_waiting,
            'duration_price'                  => $pricingRule->duration_price,
            'waiting_price'                   => $pricingRule->waiting_price,
            'distance_fee'                    => $pricingRule->distance_fee,
            'cancel_fee'                      => $pricingRule->cancel_fee,
            'agent_commission_percentage'     => $pricingRule->agent_commission_percentage,
            'agent_commission_fixed'          => $pricingRule->agent_commission_fixed,
            'freelancer_commission_percentage'=> $pricingRule->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $pricingRule->freelancer_commission_fixed,
            'unique_id'                       => $unique_order_id
        ];
        
        $orders = Order::create($order);

        //here is task save code is started

        $dep_id = null; // this is used as dependent task id 

        foreach ($request->task_type_id as $key => $value) {
            $taskcount++;
            if (isset($request->address[$key])) {
                $loc = [
                    'latitude'    => $request->latitude[$key],
                    'longitude'   => $request->longitude[$key],
                    'short_name'  => $request->short_name[$key],
                    'address'     => $request->address[$key],
                    'post_code'   => $request->post_code[$key],
                    'customer_id' => $cus_id,
                ];
                $Loction = Location::create($loc);
                $loc_id = $Loction->id;
                $send_loc_id = $loc_id;
               
            } else {
                if ($key == 0) {
                    $loc_id = $request->old_address_id;
                    $send_loc_id = $loc_id;
                } else {
                    $loc_id = $request->input('old_address_id' . $key);
                    $send_loc_id = $loc_id;
                }
            }
            
            $location = Location::where('id', $loc_id)->first();
            if ($key == 0) {
                $finalLocation = $location;
            }

            array_push($latitude,$location->latitude);
            array_push($longitude,$location->longitude);           
            
            $task_appointment_duration = empty($request->appointment_date[$key]) ? '0' : $request->appointment_date[$key];

            $data = [
                'order_id'                   => $orders->id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'appointment_duration'       => $task_appointment_duration,
                'dependent_task_id'          => $dep_id,
                'task_status'                => $agent_id != null ? 1 : 0,
                'created_at'                 => $notification_time,
                'assigned_time'              => $notification_time,
                'barcode'                    => $request->barcode[$key],
                'quantity'                   => $request->quantity[$key]
            ];
            $task = Task::create($data);
            $dep_id = $task->id;
        }

        //accounting for task duration distanse 

         $getdata = $this->GoogleDistanceMatrix($latitude,$longitude);    
         $paid_duration = $getdata['duration'] - $pricingRule->base_duration;
         $paid_distance = $getdata['distance'] - $pricingRule->base_distance;
         $paid_duration = $paid_duration < 0 ? 0 : $paid_duration;
         $paid_distance = $paid_distance < 0 ? 0 : $paid_distance;
         $total         = $pricingRule->base_price + ($paid_distance * $pricingRule->distance_fee ) + ($paid_duration * $pricingRule->duration_price);

        if(isset($agent_id)){
            $agent_details = Agent::where('id',$agent_id)->first();
            if($agent_details->type == 'Employee'){
                $percentage = $pricingRule->agent_commission_fixed + (($total / 100) * $pricingRule->agent_commission_percentage);                    
            }else{
                $percentage = $pricingRule->freelancer_commission_percentage + (($total / 100) * $pricingRule->freelancer_commission_fixed);
            }
        }

        //update order with order cost details

        $updateorder = [
            'actual_time'        => $getdata['duration'],
            'actual_distance'    => $getdata['distance'],
            'order_cost'         => $total,
            'driver_cost'        => $percentage,

         ];
        
        Order::where('id',$orders->id)->update($updateorder);


        //task tages save code is here

        if (isset($request->allocation_type) && $request->allocation_type === 'a') {
            if (isset($request->team_tag)) {
                $orders->teamtags()->sync($request->team_tag);
            }
            if (isset($request->agent_tag)) {
                $orders->drivertags()->sync($request->agent_tag);
            }
        }

        //this function is called when allocation type is Accept/Reject it find the current task location belongs to which geo fence

        $geo = null;
        if ($request->allocation_type === 'a') {
            $geo = $this->createRoster($send_loc_id);
            $agent_id = null;
        }

        // task schdule code is hare
        $allocation = AllocationRule::where('id', 1)->first();
        if($request->task_type != 'now')
        {                
            $beforetime = (int)$auth->getAllocation->start_before_task_time;                  
            $to = new \DateTime("now", new \DateTimeZone(isset(Auth::user()->timezone)? Auth::user()->timezone : 'Asia/Kolkata') );
            $sendTime = Carbon::now();                
            $to = Carbon::parse($to)->format('Y-m-d H:i:s');
            $from = Carbon::parse($notification_time)->format('Y-m-d H:i:s');                
            $datecheck = 0;
            $to_time = strtotime($to);
            $from_time = strtotime($from);               
            if($to_time >= $from_time) {
                return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
            }

            $diff_in_minutes = round(abs($to_time - $from_time) / 60);
            $schduledata = [];
            if($diff_in_minutes > $beforetime)
            {
                $finaldelay = (int)$diff_in_minutes - $beforetime;
                $time = Carbon::parse($sendTime)
                ->addMinutes($finaldelay)
                ->format('Y-m-d H:i:s');

                $schduledata['geo']               = $geo;
                $schduledata['notification_time'] = $time;
                $schduledata['agent_id']          = $agent_id;
                $schduledata['orders_id']         = $orders->id;
                $schduledata['customer']          = $customer;
                $schduledata['finalLocation']     = $finalLocation;
                $schduledata['taskcount']         = $taskcount;
                $schduledata['allocation']        = $allocation;
                $schduledata['database']          = $auth;               
                
                scheduleNotification::dispatch($schduledata)->delay(now()->addMinutes($finaldelay));
                return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
            }
        }
        
        
        //this is roster create accounding to the allocation methed
        
        if ($request->allocation_type === 'a' || $request->allocation_type === 'm') {            
            switch ($allocation->auto_assign_logic) {
                case 'one_by_one':
                    //this is called when allocation type is one by one
                    $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                case 'send_to_all':
                    //this is called when allocation type is send to all
                    $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                case 'round_robin':
                    //this is called when allocation type is round robin
                    $this->roundRobin($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                default:
                    //this is called when allocation type is batch wise 
                    $this->batchWise($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
            }
        }
        return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
    }

    public function createRoster($send_loc_id)
    {
        $getletlong = Location::where('id', $send_loc_id)->first();
        $lat = $getletlong->latitude;
        $long = $getletlong->longitude;        
        return $check = $this->findLocalityByLatLng($lat, $long);
    }

    public function findLocalityByLatLng($lat, $lng)
    {
        // get the locality_id by the coordinate //
        $latitude_y = $lat;
        $longitude_x = $lng;
        $localities = Geo::all();
        if (empty($localities))
            return false;

        foreach ($localities as $k => $locality) {
            $all_points = $locality->geo_array;
            $temp = $all_points;
            $temp = str_replace('(', '[', $temp);
            $temp = str_replace(')', ']', $temp);
            $temp = '[' . $temp . ']';
            $temp_array =  json_decode($temp, true);

            foreach ($temp_array as $k => $v) {
                $data[] = [
                    'lat' => $v[0],
                    'lng' => $v[1]
                ];
            }

            // $all_points[]= $all_points[0]; // push the first point in end to complete
            $vertices_x = $vertices_y = array();
            foreach ($data as $key => $value) {
                $vertices_y[] = $value['lat'];
                $vertices_x[] = $value['lng'];
            }

            $points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
            $points_polygon;
            if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) 
            {
                return $locality->id;
            }
        }
        return false;
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
            if ((($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])))
                $c = !$c;
        }
        return $c;
    }

    public function finalRoster($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount, $allocation)
    {   
        $allcation_type = 'AR';
        $date = \Carbon\Carbon::today();
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();       
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getPreference->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime); //this function is check the time diffrence and give the notification time
        $randem     = rand(11111111, 99999999);

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        }elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        }else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'                => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => Auth::user()->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];

            $this->dispatch(new RosterCreate($data, $extraData)); //this job is for create roster in main database for send the notification  in manual alloction
            
        } else {
            
            $unit              = $auth->getPreference->distance_unit;
            $try               = $auth->getAllocation->number_of_retries;
            $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
            $max_redius        = $auth->getAllocation->maximum_radius;
            $max_task          = $auth->getAllocation->maximum_batch_size;

            $dummyentry = [];
            $all        = [];
            $extra      = [];
            $remening   = [];
            
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs','order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get();
           
            $totalcount = $getgeo->count();
            $orders = order::where('driver_id', '!=', null)->whereDate('created_at', $date)->groupBy('driver_id')->get('driver_id');

            $allreadytaken = [];
            foreach ($orders as $ids) {
                array_push($allreadytaken, $ids->driver_id);
            }
            $counter = 0;
            $data = [];
            for ($i = 1; $i <= $try; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {
                    if (in_array($geoitem->driver_id, $allreadytaken)) {
                        $extra = [
                            'id' => $geoitem->driver_id,
                            'device_type' => $geoitem->agent->device_type, 'device_token' => $geoitem->agent->device_token
                        ];
                        array_push($remening, $extra);
                    } else {
                        $data = [
                            'order_id'            => $orders_id,
                            'driver_id'           => $geoitem->driver_id,
                            'notification_time'   => $time,
                            'type'                => $allcation_type,
                            'client_code'         => Auth::user()->code,
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $geoitem->agent->device_type,
                            'device_token'        => $geoitem->agent->device_token,
                            'detail_id'           => $randem,
                        ];
                        if (count($dummyentry) < 1) {
                            array_push($dummyentry, $data);                           
                        }

                        //here i am seting the time diffrence for every notification

                        $time = Carbon::parse($time)
                            ->addSeconds($expriedate + 3)
                            ->format('Y-m-d H:i:s');
                        array_push($all, $data);
                        $counter++;
                    }

                    if ($allcation_type == 'N' && 'ACK' && count($all) > 0) {
                        Order::where('id',$orders_id)->update(['driver_id'=>$geoitem->driver_id]);

                        break;
                    }
                }
                
                foreach($remening as $key =>  $rem){
                    $data = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $rem['id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $rem['device_type'],
                        'device_token'        => $rem['device_token'],
                        'detail_id'           => $randem,
                    ];

                        $time = Carbon::parse($time)
                        ->addSeconds($expriedate + 3)
                        ->format('Y-m-d H:i:s');

                        if (count($dummyentry) < 1) {
                            array_push($dummyentry, $data);
                        }
                        array_push($all, $data);
                        if ($allcation_type == 'N' && 'ACK' && count($all) > 0) {
                            Order::where('id',$orders_id)->update(['driver_id'=>$remening[$i]['id']]);
    
                            break;
                        }
                }
                $remening = [];
                if ($allcation_type == 'N' && 'ACK' && count($all) > 0) {
                    break;
                }
            }           
          
            $this->dispatch(new RosterCreate($all, $extraData)); // //this job is for create roster in main database for send the notification  in auto alloction            
            
        }
    }

    //this function for check time diffrence and give a time for notification send between current time and task time

    public function checkTimeDiffrence($notification_time, $beforetime)
    {
        $to   = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());
        $from = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::parse($notification_time)->format('Y-m-d H:i:s'));
        $diff_in_minutes = $to->diffInMinutes($from);
        if ($diff_in_minutes < $beforetime) {
            return  Carbon::now()->toDateTimeString();
        } else {
            return  $notification_time;
        }
    }

    public function SendToAll($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount,$allocation)
    {       
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $data = [];

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        }elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        }else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'               => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => Auth::user()->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            $this->dispatch(new RosterCreate($data, $extraData));
            
        } else {
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs','order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get();           

            for ($i = 1; $i <= $try; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {
                    $datas = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $geoitem->driver_id,
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $geoitem->agent->device_type,
                        'device_token'        => $geoitem->agent->device_token,
                        'detail_id'           => $randem,
                     ];
                    array_push($data, $datas);
                    if ($allcation_type == 'N' && 'ACK') {
                        Order::where('id',$orders_id)->update(['driver_id'=>$geoitem->driver_id]);
                        break;
                    }
                }
                $time = Carbon::parse($time)
                        ->addSeconds($expriedate + 10)
                        ->format('Y-m-d H:i:s');
                if ($allcation_type == 'N' && 'ACK') {

                    break;
                }
            }            
            $this->dispatch(new RosterCreate($data, $extraData));     
        }
    }

    public function batchWise($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount,$allocation)
    {
        
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $data = [];

       if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        }elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        }else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'               => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => Auth::user()->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            $this->dispatch(new RosterCreate($data, $extraData));
            
        } else {                        
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs' => function($g){
                        $g->orderBy('id','DESC');}
                        ,'order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get()->toArray();
           
            //this function is give me nearest drivers list accourding to the the task location.

           $distenseResult = $this->haversineGreatCircleDistance($getgeo,$finalLocation,$unit,$max_redius,$max_task);         
                     
            for ($i = 1; $i <= $try; $i++) {
                $counter = 0;
                foreach ($distenseResult as $key =>  $geoitem) {
                    $datas = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $geoitem['driver_id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $geoitem['device_type'],
                        'device_token'        => $geoitem['device_token'],
                        'detail_id'           => $randem,
                    ];
                    $counter++;
                    if($counter == $maxsize){
                        $time = Carbon::parse($time)
                        ->addSeconds($expriedate)
                        ->format('Y-m-d H:i:s');
                        $counter = 0;
                    }
                    array_push($data, $datas);
                    if ($allcation_type == 'N' && 'ACK') {
                        break;
                    }
                }
                $time = Carbon::parse($time)
                ->addSeconds($expriedate + 10)
                ->format('Y-m-d H:i:s');

                if ($allcation_type == 'N' && 'ACK') {
                    break;
                }
            } 
            $this->dispatch(new RosterCreate($data, $extraData)); // job for create roster            
        }
    }


    public function roundRobin($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount)
    {
        
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $data = [];

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        }elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        }else {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $customer->name,
            'customer_phone_number'    => $customer->phone_number,
            'short_name'               => $finalLocation->short_name,
            'address'                  => $finalLocation->address,
            'lat'                      => $finalLocation->latitude,
            'long'                     => $finalLocation->longitude,
            'task_count'               => $taskcount,
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        if (!isset($geo)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => Auth::user()->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            $this->dispatch(new RosterCreate($data, $extraData));
        } else {           
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs','order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get()->toArray();
           
           //this function give me the driver list accourding to who have liest task for the current date

           $distenseResult = $this->roundCalculation($getgeo,$finalLocation,$unit,$max_redius,$max_task);          
           
            for ($i = 1; $i <= $try; $i++) {                
                foreach ($distenseResult as $key =>  $geoitem) {
                    $datas = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $geoitem['driver_id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $geoitem['device_type'],
                        'device_token'        => $geoitem['device_token'],
                        'detail_id'           => $randem,
                    ];                   
                    $time = Carbon::parse($time)
                    ->addSeconds($expriedate)
                    ->format('Y-m-d H:i:s');
                    array_push($data, $datas);
                    if ($allcation_type == 'N' && 'ACK') {
                        break;
                    }
                }

                $time = Carbon::parse($time)
                    ->addSeconds($expriedate +10)
                    ->format('Y-m-d H:i:s');
                if ($allcation_type == 'N' && 'ACK') {
                    break;
                }
            }            
            
            $this->dispatch(new RosterCreate($data, $extraData));      // job for insert data in roster table for send notification
            
        }
    }

    public function roundCalculation($getgeo,$finalLocation,$unit,$max_redius,$max_task)
    {   
        $extraarray    = [];        
        foreach($getgeo as $item){            
            $count = isset($item['agent']['order']) ? count($item['agent']['order']):0;            
            if($max_task > $count){                
                $data = [
                    'driver_id'    =>  $item['agent']['id'],
                    'device_type'  =>  $item['agent']['device_type'], 
                    'device_token' =>  $item['agent']['device_token'],
                    'task_count'   =>  $count,
                ];                    
                array_push($extraarray,$data); 
            }              
        }
        $allsort = array_values(Arr::sort($extraarray, function ($value) {
            return $value['task_count'];
        }));       
        return $allsort;
    }
    
    function haversineGreatCircleDistance($getgeo,$finalLocation,$unit,$max_redius,$max_task)
    {        
        // convert from degrees to radians
        $earthRadius = 6371;  // earth radius in km
        $latitudeFrom  = $finalLocation->latitude;
        $longitudeFrom = $finalLocation->longitude;
        $lastarray     = [];
        $extraarray    = [];
        foreach($getgeo as $item){
            $latitudeTo  = $item['agent']['logs']['lat'];
            $longitudeTo = $item['agent']['logs']['long'];            
            if(isset($latitudeFrom) && isset($latitudeFrom) && isset($latitudeTo) && isset($longitudeTo))
            {
                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo   = deg2rad($latitudeTo);
                $lonTo   = deg2rad($longitudeTo);          
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;          
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
             
                $final = round($angle * $earthRadius);
                $count = isset($item['agent']['order']) ? count($item['agent']['order']):0;
                if($unit == 'metric')
                {                    
                    if($final <= $max_redius && $max_task > $count )
                    {
                        $data = [
                            'driver_id'    =>  $item['agent']['logs']['agent_id'],
                            'device_type'  =>  $item['agent']['device_type'], 
                            'device_token' =>  $item['agent']['device_token'],
                            'distance'     =>  $final
                        ];
                        array_push($extraarray,$data); 
                    }
                }else{                    
                    if($final <= $max_redius && $max_task > $count ){
                        $data = [
                            'driver_id'    =>  $item['agent']['logs']['agent_id'],
                            'devide_type'  =>  $item['agent']['device_type'], 
                            'device_token' =>  $item['agent']['device_token'],
                            'distance'     =>  round($final * 0.6214)
                        ];
                        array_push($extraarray,$data);                        
                    } 
                }
            }
        }
          
        $allsort = array_values(Arr::sort($extraarray, function ($value) {
            return $value['distance'];
        }));
       
        return $allsort;
    }

    public function GoogleDistanceMatrix($latitude,$longitude)
    {        
        $send   = []; 
        $client = ClientPreference::where('id',1)->first();
        $lengths = count($latitude)-1;
        $value = [];
        $count  = 0;
        $count1 = 1;
        for($i = 0; $i<$lengths; $i++) {
            $ch = curl_init();
            $headers = array('Accept: application/json',
                    'Content-Type: application/json',
                    );
            $url =  'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$latitude[$count].','.$longitude[$count].'&destinations='.$latitude[$count1].','.$longitude[$count1].'&key='.$client->map_key_1.'';
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch); // Close the connection
            $new =   $result;
            array_push($value,$result->rows[0]->elements);
            $count++;
            $count1++;
        }
      
        if(isset($value)){
            $totalDistance = 0;
            $totalDuration = 0;
            foreach($value as $item){
                //dd($item);
                $totalDistance = $totalDistance + $item[0]->distance->value;
                $totalDuration = $totalDuration + $item[0]->duration->value;
            }           
           
            if($client->distance_unit == 'metric'){
                $send['distance'] = round($totalDistance/1000, 2);      //km
            }else{
                $send['distance'] = round($totalDistance/1609.34, 2);  //mile
            }
            
            $newvalue = round($totalDuration/60, 2);
            $whole = floor($newvalue); 
            $fraction = $newvalue - $whole;

            if($fraction >= 0.60){
                $send['duration'] = $whole + 1;
            }else{
                $send['duration'] = $whole;
            } 
            
        }
        
        return $send;        

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
   

    public function edit($domain = '',$id)
    {        
        $savedrivertag   = [];
        $saveteamtag     = [];
        //=> function($o){$o->where('short_name','!=',null);}
        $task            = Order::where('id', $id)->with(['task','agent','customer.location'])->first();
        $fatchdrivertag  = TaskDriverTag::where('task_id', $id)->get('tag_id');
        $fatchteamtag    = TaskTeamTag::where('task_id', $id)->get('tag_id');
        if (count($fatchdrivertag) > 0 && count($fatchteamtag) > 0) {
            foreach ($fatchdrivertag as $key => $value) {
                array_push($savedrivertag, $value->tag_id);
            }
            foreach ($fatchteamtag as $key => $value) {
                array_push($saveteamtag, $value->tag_id);
            }
        }
        //for s3 base url
        $can = Storage::disk('s3')->url('image.png');
        $lastbaseurl = str_replace('image.png', '', $can);

        $teamTag        = TagsForTeam::all();
        $agentTag       = TagsForAgent::all();
        $agents         = Agent::orderBy('created_at', 'DESC')->get();

        if (isset($task->images_array)) {
            $array = explode(",", $task->images_array);
        } else {
            $array = '';
        }
        
        $all_locations = array();
        $address_preference  = ClientPreference::where('id',1)->first(['allow_all_location']);
        if($address_preference->allow_all_location==1)
        {
            $cust_id = $task->customer_id;
            $all_locations = Location::where('customer_id','!=', $cust_id)->where('short_name','!=',null)->where('location_status',1)->get();
        } 
        return view('tasks/update-task')->with(['task' => $task, 'teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'images' => $array, 'savedrivertag' => $savedrivertag, 'saveteamtag' => $saveteamtag, 'main' => $lastbaseurl,'alllocations'=>$all_locations]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$domain = '',$id)
    {          
        $iinputs = $request->toArray();
        $old_address_ids = array();
        foreach($iinputs as $key => $value)
        {
            if(substr_count($key,"old_address_id") == 1)
            {
                $old_address_ids[] = $key;
            }
        }        

        $task_id = Order::find($id);
        $validator = $this->validator($request->all())->validate();
        $loc_id = 0;
        $cus_id = 0;
        $percentage = 0;

        $images = [];
        $last = '';
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_' . $folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();

                $s3filePath = '/assets/' . $folder . '/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
                array_push($images, $path);

            }
            $last = implode(",", $images);
        }

        if (!isset($request->ids)) {
            $customer = Customer::where('email', '=', $request->email)->first();
            if (isset($customer->id)) {
                $cus_id = $customer->id;
            } else {
                $cus = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ];
                $customer = Customer::create($cus);
                $cus_id = $customer->id;
            }
        } else {
            $cus_id = $request->ids;
            $customer = Customer::where('id', $request->ids)->first();
        }
        $assign = 'unassigned';
        if($request->allocation_type == 'm'){
            $assign = 'assigned';
        }

        $pricingRule = PricingRule::where('id',1)->first();
        $agent_id =  isset($request->allocation_type) && $request->allocation_type == 'm' ? $request->agent : null;
        
        if(isset($agent_id) && $task_id->driver_cost <= 0.00){
           
            $agent_details = Agent::where('id',$agent_id)->first();
            if($agent_details->type == 'Employee'){
                $percentage = $pricingRule->agent_commission_fixed + (($task_id->order_cost / 100) * $pricingRule->agent_commission_percentage);  
                
            }else{
                $percentage = $pricingRule->freelancer_commission_percentage + (($task_id->order_cost / 100) * $pricingRule->freelancer_commission_fixed);
            }
            $this->MassAndEditNotification($id,$agent_id);
        }
        
        if($task_id->driver_cost != 0.00){
            $percentage = $task_id->driver_cost;
        }       
        
        $settime = ($request->task_type=="schedule") ? $request->schedule_time : Carbon::now()->toDateTimeString();
        $notification_time = ($request->task_type=="schedule")? Carbon::parse($settime . $auth->timezone ?? 'UTC')->tz('UTC') : Carbon::now()->toDateTimeString();

        $order = [
            'customer_id'                => $cus_id,
            'recipient_phone'            => $request->recipient_phone,
            'Recipient_email'            => $request->Recipient_email,
            'task_description'           => $request->task_description,
            'driver_id'                  => $agent_id,
            'order_type'                 => $request->task_type,            
            'order_time'                 => $notification_time,
            'auto_alloction'             => $request->allocation_type,
            'cash_to_be_collected'       => $request->cash_to_be_collected,
            'status'                     => $assign,
            'driver_cost'                => $percentage,
        ];
        $orders = Order::where('id', $id)->update($order);
        if ($last != '') {
            $orderimages = Order::where('id', $id)->update(['images_array' => $last]);
        }

        Task::where('order_id', $id)->delete();
        $dep_id = null;
        foreach ($request->task_type_id as $key => $value) {

            if (isset($request->short_name[$key])) {
                $loc = [
                    'short_name' => $request->short_name[$key],
                    'address'    => $request->address[$key],
                    'post_code'  => $request->post_code[$key],
                    'latitude'   => $request->latitude[$key],
                    'longitude'  => $request->longitude[$key],
                    'email'  => $request->address_email[$key],
                    'phone_number'  => $request->address_phone_number[$key],
                    // 'due_after'  => $request->due_after[$key],
                    // 'due_before'  => $request->due_before[$key],
                    'customer_id' => $cus_id,
                ];
                $Loction = Location::create($loc);
                $loc_id = $Loction->id;
            } else {
                if ($key == 0) {
                    $loc_id = $request->old_address_id;
                } else {
                    $loc_id = $request->input($old_address_ids[$key]);
                }

                $location = Location::where('id', $loc_id)->first();
                if($location->customer_id != $cus_id)
                {
                    $newloc = [
                        'latitude'    => $location->latitude,
                        'longitude'   => $location->longitude,
                        'short_name'  => $location->short_name,
                        'address'     => $location->address,
                        'post_code'   => $location->post_code,
                        'email'         => $location->address_email,
                        'phone_number'   => $location->address_phone_number,
                        // 'due_after'      => $location->due_after,
                        // 'due_before'    => $location->due_before,
                        'customer_id' => $cus_id,
                    ];
                    $location = Location::create($newloc);
                }                
                $loc_id = $location->id;
            }

            $data = [
                'order_id'                   => $id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'allocation_type'            => $request->allocation_type,
                'dependent_task_id'          => $dep_id,
                'task_status'                => isset($agent_id) ? 1 : 0,
                'barcode'                    => $request->barcode[$key],
                'quantity'                   => $request->quantity[$key],
                'assigned_time'              => $notification_time,
            ];
            $task = Task::create($data);
            $dep_id = $task->id;
        }

        if (isset($request->allocation_type) && $request->allocation_type === 'a') {
            if (isset($request->team_tag)) {
                $task_id->teamtags()->sync($request->team_tag);
            }
            if (isset($request->agent_tag)) {
                $task_id->drivertags()->sync($request->agent_tag);
            }
        } else {
            $teamTag = [];
            $drivertag = [];
            $task_id->teamtags()->sync($teamTag);
            $task_id->drivertags()->sync($drivertag);
        }

        //sending silent push notification
        if($agent_id!="")
        {
            $allcation_type = 'silent';
            //$randem     = rand(11111111, 99999999);
            $oneagent = Agent::where('id', $agent_id)->first();
            $notification_data = [
                'title'               => 'Update Order',
                'body'                => 'Check All Details For This Request In App',
                'order_id'            => $id,
                'driver_id'           => $agent_id,
                'notification_time'   => Carbon::now()->toDateTimeString(),
                'type'                => $allcation_type,
                'client_code'         => Auth::user()->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => '',
            ];           
            $this->sendsilentnotification($notification_data);           
        }
        return redirect()->route('tasks.index')->with('success', 'Task Updated successfully!');
    }

    // this function for sending silent notification
    public function sendsilentnotification($notification_data)
    {  
        $new = [];
        array_push($new,$notification_data['device_token']);
        if(isset($new)){
            fcm()
            ->to($new) // $recipients must an array
            ->data($notification_data)
            ->notification([
                'sound' =>  'default',
            ])
            ->send();
        }          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '',$id)
    {
        Order::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    //this is for serch customer for when create tasking

    public function search(Request $request,$domain = '')
    {

        $search = $request->search;
        if (isset($search)) {
            if ($search == '') {
                $employees = Customer::orderby('name', 'asc')->select('id', 'name')->limit(10)->get();
            } else {
                $employees = Customer::orderby('name', 'asc')->select('id', 'name')->where('name', 'like', '%' . $search . '%')->limit(10)->get();
            }
            $response = array();
            foreach ($employees as $employee) {
                $response[] = array("value" => $employee->id, "label" => $employee->name);
            }

            return response()->json($response);
        } else {
            $id = $request->id;
            $address_preference  = ClientPreference::where('id',1)->first(['allow_all_location']);
            if($address_preference->allow_all_location==1)
            {   // show all address                
                $myloctions = Location::where('customer_id', $id)->where('short_name','!=',null)->where('location_status',1)->get();
                $allloctions = Location::where('customer_id','!=', $id)->where('short_name','!=',null)->where('location_status',1)->get();
                $loction = array_merge($myloctions->toArray(),$allloctions->toArray());
                return response()->json($loction);
            }else{
                $loction = Location::where('customer_id', $id)->where('short_name','!=',null)->where('location_status',1)->get();
                return response()->json($loction);
            }           
        }
    }


    //this function is give task list of an order
    public function tasklist($domain = '',$id)
    {        
        $task = Order::where('id', $id)->with(['task.location'])->first();
        $client = ClientPreference::where('id',1)->first();
        $agent = Agent::where('id',$task->driver_id)->first();
        $task = $task->toArray();
        $task['driver_type']   = isset($agent->type) ? $agent->type :'';
        $task['distance_type'] = $client->distance_unit == 'metric' ? 'Km':'Mile';
        return response()->json($task);        
    }
}
