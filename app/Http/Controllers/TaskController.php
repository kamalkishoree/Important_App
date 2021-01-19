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
use App\Model\{Agent, AllocationRule, Client, ClientPreference, DriverGeo, PricingRule, Roster};
use App\Model\Geo;
use App\Model\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Jobs\RosterCreate;
use App\Models\RosterDeatil;
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
        $tasks = Order::orderBy('created_at', 'DESC')->with(['customer', 'location', 'taskFirst', 'agent', 'task']);
        $check = '';
        if ($request->has('status') && $request->status != 'all') {

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
        $tasks    =  $tasks->paginate(10);


        $pricingRule = PricingRule::select('id', 'name')->get();
        $teamTag    = TagsForTeam::all();
        $agentTag   = TagsForAgent::all();

        return view('tasks/task')->with(['tasks' => $tasks, 'status' => $request->status, 'active_count' => $active, 'panding_count' => $pending, 'history_count' => $history, 'status' => $check ]);
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

        /*$pricingRule = PricingRule::select('id', 'name')->whereDate('start_date_time', '<', Carbon::now())
                            ->whereDate('end_date_time', '>', Carbon::now())->get();*/


        //$agents = Agent::orderBy('created_at', 'DESC')->where('is_activated', 1)->get();
        $agents = Agent::orderBy('created_at', 'DESC')->get();
        //print_r($agents);die;

        $returnHTML = view('modals/add-task-modal')->with(['teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'pricingRule' => $pricingRule, 'allcation' => $allcation])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));

        //return view('tasks/add-task')->with(['teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'pricingRule' => $pricingRule]);
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


        $validator   = $this->validator($request->all())->validate();
        $loc_id = $cus_id = $send_loc_id = $newlat = $newlong = 0;

        $images = [];
        $last = '';
        $customer = [];
        $finalLocation = [];
        $taskcount = 0;
        $latitude  = [];
        $longitude = [];
        $percentage = 0;

        // dd($request->all());

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
                // $can = Storage::disk('s3')->url('image.png');
                // $last = str_replace('image.png', '', $can);

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
            $pricingRule = PricingRule::where('id',1)->first();

        //order save

        $notification_time = isset($request->schedule_time) ? $request->schedule_time : Carbon::now()->toDateTimeString();
        $agent_id        = $request->allocation_type === 'm' ? $request->agent : null;
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
            'freelancer_commission_fixed'     => $pricingRule->freelancer_commission_fixed
        ];

        $orders = Order::create($order);

        //task save

        $dep_id = null;
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
           

            
            $task_allo_type = empty($request->appointment_date[$key]) ? '0' : $request->appointment_date[$key];

            $data = [
                'order_id'                   => $orders->id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'allocation_type'            => $task_allo_type,
                'dependent_task_id'          => $dep_id,
                'task_status'                => $agent_id != null ? 1 : 0,
                'created_at'                 => $notification_time
            ];
            if (!empty($request->pricing_rule_id)) {
                $data['pricing_rule_id'] = $request->pricing_rule_id;
            }
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
         $updateorder = [
            'actual_time'        => $getdata['duration'],
            'actual_distance'    => $getdata['distance'],
            'order_cost'         => $total,
            'driver_cost'        => $percentage,

         ];
         


         Order::where('id',$orders->id)->update($updateorder);


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

        //this is roster create accounding to the allocation methed

        if ($request->allocation_type === 'a' || $request->allocation_type === 'm') {
            $allocation = AllocationRule::where('id', 1)->first();
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
                    $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
                    break;
                default:
                    //this is called when allocation type is batch wise 
                    $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $finalLocation, $taskcount, $allocation);
            }
        }



        return redirect()->route('tasks.index')->with('success', 'Task Added Successfully!');
    }



    public function createRoster($send_loc_id)
    {
        

        $getletlong = Location::where('id', $send_loc_id)->first();
        $lat = $getletlong->latitude;
        $long = $getletlong->longitude;

        //$allgeo     = Geo::all();

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

            if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) {

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
        //dd($customer);
        // print($geo);
        // print($notification_time);
        // print($agent_id);
        // print($orders_id);
        // die;
        $allcation_type = 'AR';
        $date = \Carbon\Carbon::today();
        $auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getAllocation->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem     = rand(11111111, 99999999);

        if ($type != 'acceptreject') {
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

            $this->dispatchNow(new RosterCreate($data, $extraData));
            return $task = Roster::create($data);
        } else {

            $dummyentry = [];
            $all        = [];
            $extra      = [];
            $remening   = [];
            $getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');

            $totalcount = $getgeo->count();
            $orders = order::where('driver_id', '!=', null)->whereDate('created_at', $date)->groupBy('driver_id')->get('driver_id');

            $allreadytaken = [];
            foreach ($orders as $ids) {
                array_push($allreadytaken, $ids->driver_id);
            }
            //print_r($allreadytaken);
            $counter = 0;
            $data = [];
            //for ($i = 1; $i <= $try; $i++) {
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
                            array_push($dummyentry, $data);
                            array_push($dummyentry, $data);
                        }
                        $time = Carbon::parse($time)
                            ->addSeconds($expriedate)
                            ->format('Y-m-d H:i:s');
                        array_push($all, $data);
                        $counter++;
                    }

                    if ($allcation_type == 'N' && count($all) > 0) {
                        Order::where('id',$orders_id)->update(['driver_id'=>$geoitem->driver_id]);

                        break;
                    }
                }
                // if ($allcation_type == 'N' && count($all) > 0) {

                //     break;
                // }
            //}

            if ($totalcount > $counter) {
                $loopcount =  $totalcount - $counter;

                for ($i = 0; $i < $loopcount; $i++) {
                    if ($allcation_type == 'N' && count($all) > 0) {

                        break;
                    }

                    $data = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $remening[$i]['id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => Auth::user()->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $remening[$i]['device_type'],
                        'device_token'        => $remening[$i]['device_token'],
                        'detail_id'           => $randem,
                    ];
                    if (count($dummyentry) < 1) {
                        array_push($dummyentry, $data);
                        array_push($dummyentry, $data);
                        array_push($dummyentry, $data);
                    }
                    $time = Carbon::parse($time)
                        ->addSeconds($expriedate)
                        ->format('Y-m-d H:i:s');
                    array_push($all, $data);
                    if ($allcation_type == 'N' && count($all) > 0) {
                        Order::where('id',$orders_id)->update(['driver_id'=>$remening[$i]['id']]);

                        break;
                    }
                }
            }

            $this->dispatchNow(new RosterCreate($all, $extraData));
            return Roster::create($dummyentry);
        }
    }
    public function checkTimeDiffrence($notification_time, $beforetime)
    {
        // print($now);
        // print($notification_time);



        $to   = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now()->toDateTimeString());

        $from = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::parse($notification_time)->format('Y-m-d H:i:s'));

        $diff_in_minutes = $to->diffInMinutes($from);
        if ($diff_in_minutes < $beforetime) {
            return  Carbon::now()->toDateTimeString();
        } else {
            return  $notification_time;
        }
    }

    public function SendToAll($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount)
    {
        $allcation_type = 'AR';
        $date       = \Carbon\Carbon::today();
        $auth       = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getPreference->acknowledgement_type;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem     = rand(11111111, 99999999);
        $data = [];
        if ($type != 'acceptreject') {
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
            $this->dispatchNow(new RosterCreate($data, $extraData));
            return $task = Roster::create($data);
        } else {

            $getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');
            

            //for ($i = 1; $i <= $try; $i++) {
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
                    if ($allcation_type == 'N') {
                        Order::where('id',$orders_id)->update(['driver_id'=>$geoitem->driver_id]);
                        break;
                    }
                }

            //     if ($allcation_type == 'N') {

            //         break;
            //     }
            // }
            $this->dispatch(new RosterCreate($data, $extraData));
            return $task = Roster::create($data[0]);
            //die('hello');
        }
    }

    public function batchWise($geo, $notification_time, $agent_id, $orders_id, $customer, $finalLocation, $taskcount)
    {
        
        $allcation_type = 'AR';
        $date       = \Carbon\Carbon::today();
        $auth       = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
        $expriedate = (int)$auth->getAllocation->request_expiry;
        $beforetime = (int)$auth->getAllocation->start_before_task_time;
        $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
        $type       = $auth->getPreference->acknowledgement_type;
        $unit       = $auth->getPreference->distance_unit;
        $try        = $auth->getAllocation->number_of_retries;
        $time       = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem     = rand(11111111, 99999999);
        $data = [];
        if ($type != 'acceptreject') {
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
            return $task = Roster::create($data);
        } else {

            //$getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent.logs'=> function($o){
                    $o->orderBy('id','DESC');
                }])->get()->toArray();
            
            $this->haversineGreatCircleDistance($getgeo,$finalLocation,$unit);

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
                    if ($allcation_type == 'N') {

                        break;
                    }
                }

                if ($allcation_type == 'N') {

                    break;
                }
            }
            $this->dispatch(new RosterCreate($data, $extraData));
            return $task = Roster::create($data[0]);
        }
    }


    
    function haversineGreatCircleDistance($getgeo,$finalLocation,$unit)
    {
        //$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, 
        // convert from degrees to radians

        $earthRadius = 6371;  // earth radius in km
        $latitudeFrom  = $finalLocation->latitude;
        $longitudeFrom = $finalLocation->longitude;
        $lastarray     = [];
        foreach($getgeo as $item){
            $latitudeTo  = $item['agent']['logs']['lat'];
            $longitudeTo = $item['agent']['logs']['long'];
            if(isset($latitudeFrom) && isset($latitudeFrom) && isset($latitudeTo) && isset($longitudeTo)){

                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo   = deg2rad($latitudeTo);
                $lonTo   = deg2rad($longitudeTo);
          
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
          
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                
                if($unit == 'metric'){
                    $lastarray[$item['agent']['logs']['agent_id']] = round($angle * $earthRadius);
                }else{
                    $lastarray[$item['agent']['logs']['agent_id']] = round(($angle * $earthRadius) * 0.6214);
                }
                
                //return $angle * $earthRadius;
                

            }
            

        }
        

          
    }

    public function GoogleDistanceMatrix($latitude,$longitude)
    {
        $send   = []; 
        $client = ClientPreference::where('id',1)->first();
        $lengths = count($latitude) - 1;
        $value = [];
      for($i = 1; $i<=$lengths; $i++) {
        $count  = 0;
        $count1 = 1;
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
          //dd($result);

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
            // 
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
    




    // public function getGeoCoordinatesAttribute($geoarray){
    //     $data = [];  
    //     $temp = $geoarray;
    //     $temp = str_replace('(','[',$temp);
    //     $temp = str_replace(')',']',$temp);
    //     $temp = '['.$temp.']';
    //     $temp_array =  json_decode($temp,true);

    //     foreach($temp_array as $k=>$v){
    //         $data[] = [
    //             'lat' => $v[0],
    //             'lng' => $v[1]
    //         ];
    //     }
    //     return $data;
    // }

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

        $savedrivertag   = [];
        $saveteamtag     = [];
        $task            = Order::where('id', $id)->with(['customer.location', 'task', 'agent'])->first();
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

        $pricingRule = PricingRule::select('id', 'name')->get();

        /*$pricingRule = PricingRule::select('id', 'name')->whereDate('start_date_time', '<', Carbon::now())
                            ->whereDate('end_date_time', '>', Carbon::now())->get();*/

        return view('tasks/update-task')->with(['task' => $task, 'teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'images' => $array, 'savedrivertag' => $savedrivertag, 'saveteamtag' => $saveteamtag, 'main' => $lastbaseurl, 'pricingRule' => $pricingRule]);
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

        $task_id = Order::find($id);
        $validator = $this->validator($request->all())->validate();
        $loc_id = 0;
        $cus_id = 0;
        $percentage = 0;

        $images = [];
        $last = '';
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
                // $can = Storage::disk('s3')->url('image.png');
                // $last = str_replace('image.png', '', $can);

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
        $assign = '';
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
        }
        if($task_id->driver_cost != 0.00){
            $percentage = $task_id->driver_cost;
        }
        
        

        $order = [
            'customer_id'                => $cus_id,
            'recipient_phone'            => $request->recipient_phone,
            'Recipient_email'            => $request->Recipient_email,
            'task_description'           => $request->task_description,
            'driver_id'                  => $agent_id,
            'order_type'                 => $request->task_type,
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
                    'customer_id' => $cus_id,
                ];
                $Loction = Location::create($loc);
                $loc_id = $Loction->id;
            } else {
                if ($key == 0) {
                    $loc_id = $request->old_address_id;
                } else {
                    $loc_id = $request->input('old_address_id' . $key);
                }
            }

            $data = [
                'order_id'                   => $id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'allocation_type'            => $request->allocation_type,
                'dependent_task_id'          => $dep_id,
                'task_status'                => isset($agent_id) ? 1 : 0
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


        return redirect()->route('tasks.index')->with('success', 'Task Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function search(Request $request)
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
            $loction = Location::where('customer_id', $id)->where('short_name','!=',null)->get();
            return response()->json($loction);
        }
    }

    public function tasklist($id)
    {
        
            $task = Order::where('id', $id)->with('task.location')->first();
            return response()->json($task);
        
        

        # code...
    }
}
