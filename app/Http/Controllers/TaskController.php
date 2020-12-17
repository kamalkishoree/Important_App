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
use App\Model\{Agent, Client, DriverGeo, PricingRule, Roster};
use App\Model\Geo;
use App\Model\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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
        $tasks = Order::orderBy('created_at', 'DESC')->with(['customer', 'location', 'taskFirst','agent']);
        if($request->has('status') && $request->status != 'all'){
            $tasks = $tasks->where('status', $request->status);
        }
        $tasks = $tasks->paginate(10);
        
    
        
        
        return view('tasks/task')->with(['tasks' => $tasks, 'status' =>$request->status]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamTag    = TagsForTeam::all();
        $agentTag   = TagsForAgent::all();
        $pricingRule = PricingRule::select('id', 'name')->get();

        /*$pricingRule = PricingRule::select('id', 'name')->whereDate('start_date_time', '<', Carbon::now())
                            ->whereDate('end_date_time', '>', Carbon::now())->get();*/


        //$agents = Agent::orderBy('created_at', 'DESC')->where('is_activated', 1)->get();
        $agents = Agent::orderBy('created_at', 'DESC')->get();

        return view('tasks/add-task')->with(['teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'pricingRule' => $pricingRule]);
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
        $loc_id      = 0;
        $cus_id      = 0;
        $send_loc_id = 0;

        $images = [];
        $last = '';

       // dd($request->all());
        
        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_'.$folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                
                $s3filePath = '/assets/'.$folder.'/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file,'public');
                array_push($images, $path);
                // $can = Storage::disk('s3')->url('image.png');
                // $last = str_replace('image.png', '', $can);
               
            }
               $last = implode(",", $images);
        }
        if (!isset($request->ids)) {
            $cus = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];
            $customer = Customer::create($cus);
            $cus_id = $customer->id;
        } else {
            $cus_id = $request->ids;
        }
          
        $notification_time = isset($request->schedule_time)?$request->schedule_time:Carbon::now()->toDateTimeString();
        $agent_id        = $request->allocation_type === 'm' ? $request->agent:null;
        $order = [
            'customer_id'                => $cus_id,
            'recipient_phone'            => $request->recipient_phone,
            'Recipient_email'            => $request->recipient_email,
            'task_description'           => $request->task_description,
            'driver_id'                  => $agent_id,
            'auto_alloction'             => $request->allocation_type,
            'images_array'               => $last,
            'order_type'                 => $request->task_type,
            'order_time'                 => $notification_time,
            'status'                     => $agent_id != null ? 'assigned' :'unassigned'
        ];
        $orders = Order::create($order);

       
        $dep_id = null;
        foreach ($request->task_type_id as $key => $value) {

            if (isset($request->short_name[$key])) {
                $loc = [
                    'short_name' => $request->short_name[$key],
                    'address'    => $request->address[$key],
                    'post_code'  => $request->post_code[$key],
                    'created_by' => $cus_id,
                ];
               $Loction = Location::create($loc);
               $loc_id = $Loction->id;
            } else {
                if($key == 0){
                    $loc_id = $request->old_address_id;
                    $send_loc_id = $loc_id;
                }else{
                    $loc_id = $request->input('old_address_id'.$key);
                   
                }
                
            }
            $task_allo_type = empty($request->appointment_date[$key]) ? '0' : $request->appointment_date[$key];

            $data = [
                'order_id'                   => $orders->id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'allocation_type'            => $task_allo_type,            
                'dependent_task_id'          => $dep_id,
                'task_status'                => $agent_id != null ? 1 : 0,
            ];
            if(!empty($request->pricing_rule_id)){
                $data['pricing_rule_id'] = $request->pricing_rule_id;
            }
            $task = Task::create($data);
            $dep_id = $task->id;
        }
       

        if (isset($request->allocation_type) && $request->allocation_type === 'a') {
            if (isset($request->team_tag)) {
                $orders->teamtags()->sync($request->team_tag);
            }
            if (isset($request->agent_tag)) {
                $orders->drivertags()->sync($request->agent_tag);
            }
        }
         $geo = null;
        if($request->allocation_type === 'a'){
            $geo = $this->createRoster($send_loc_id);
            $agent_id = null;
        }
         
        if($request->allocation_type === 'a' || $request->allocation_type === 'm'){
            $this->finalRoster($geo,$notification_time,$agent_id,$orders->id);
        }
        
         
         
        return redirect()->route('tasks.index')->with('success', 'Task Added successfully!');
    }


    public function createRoster($location_id)
    {
        
        $getletlong = Location::where('id',$location_id)->first();
        $lat = $getletlong->latitude;
        $long = $getletlong->longitude;
        //$allgeo     = Geo::all();

        return $check = $this->findLocalityByLatLng($lat,$long);

         
    }


    public function findLocalityByLatLng($lat,$lng){
        // get the locality_id by the coordinate //
 
        $latitude_y = $lat;
        $longitude_x = $lng;
 
        $localities = Geo::all();
 
        if(empty($localities))
            return false;
 
 
        foreach ($localities as $k => $locality) {
            
            $all_points = $locality->geo_array;
            $temp = $all_points;
            $temp = str_replace('(','[',$temp);
            $temp = str_replace(')',']',$temp);
            $temp = '['.$temp.']';
            $temp_array =  json_decode($temp,true);
    
            foreach($temp_array as $k=>$v){
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
            
            if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                
              return $locality->id;
            }
        }
 
        return false;
    }
 
    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
          $i = $j = $c = 0;
          for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
             ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
               $c = !$c;
          }
          return $c;
    }

    public function finalRoster($geo,$notification_time,$agent_id,$orders_id)
    {
        $date = \Carbon\Carbon::today();
        $auth = Client::where('code',Auth::user()->code)->with('getAllocation')->first();
        
        if(!isset($geo)){
            $data = [
                'order_id'            => $orders_id,
                'driver_id'           => $agent_id,
                'notification_time'   => $notification_time,
                'type'                => 'N',
            ];
            $task = Roster::create($data);
        } else {

            $expriedate = (int)$auth->getAllocation->request_expiry;
            $beforetime = (int)$auth->getAllocation->start_before_task_time;
            $maxsize    = (int)$auth->getAllocation->maximum_batch_size;
            $time       = $this->checkTimeDiffrence(Carbon::now()->toDateTimeString(),$notification_time,$beforetime);
            
           
            $all   = [];
            $extra = [];
            $getgeo = DriverGeo::where('geo_id',$geo)->get('driver_id');
            

            $totalcount = $getgeo->count();
            $orders = order::where('driver_id','!=',null)->whereDate('created_at',$date)->groupBy('driver_id')->get('driver_id');
            
            $allreadytaken = [];
            foreach($orders as $ids){
                array_push($allreadytaken,$ids->driver_id);
            }
            //print_r($allreadytaken);
            $counter = 0;
            $remening = [];
            foreach($getgeo as $key =>  $geoitem){
                if($counter <= $maxsize){
                    $data = [];
                    if(in_array($geoitem->driver_id, $allreadytaken)){
                           
                        array_push($remening,$geoitem->driver_id);
                    } else{
                        
                        $data = [
                        'order_id'            => $orders_id,
                        'driver_id'           => $geoitem->driver_id,
                        'notification_time'   => $time,
                        'type'                => 'AR',
                        ];
                        $time = Carbon::parse($time)
                        ->addSeconds($expriedate)
                        ->format('Y-m-d H:i:s');
                        array_push($all,$data);
                        $counter++;
                    }
                    //print_r($remening);
                }else{
                    break;
                }
                
                
            }
            //echo $counter;
            //echo $totalcount;
           
            // print_r($getgeo);
            // print_r($all->toarray());
            // print_r($orders->toarray());
            //print_r($remening);
            if($totalcount > $counter){
               $loopcount =  $totalcount - $counter;
              
               for($i=0;$i>$loopcount;$i++){
                   
                $data = [
                    'order_id'            => $orders_id,
                    'driver_id'           => $remening[$i],
                    'notification_time'   => $time,
                    'type'                => 'pr',
                    ];
                    $time = Carbon::parse($time)
                    ->addSeconds($expriedate)
                    ->format('Y-m-d H:i:s');
                    array_push($all,$data);
               } 
            }
            // die();
            Roster::insert($all);
        }
        

    }
    public function checkTimeDiffrence($now,$notification_time,$beforetime)
    {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$now);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$notification_time);
          $diff_in_minutes = $to->diffInMinutes($from);
        if($diff_in_minutes < $beforetime){
            return  Carbon::now()->toDateTimeString();
        }else{
            return  $notification_time;
        }
        
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
        
        $savedrivertag = [];
        $saveteamtag   = [];
        $task           = Order::where('id', $id)->with(['customer.location','task','agent'])->first();
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
       
        if(isset($task->images_array)){
            $array = explode(",", $task->images_array);
        }else{
            $array = '';
        }

        $pricingRule = PricingRule::select('id', 'name')->get();

        /*$pricingRule = PricingRule::select('id', 'name')->whereDate('start_date_time', '<', Carbon::now())
                            ->whereDate('end_date_time', '>', Carbon::now())->get();*/

        return view('tasks/update-task')->with(['task' => $task, 'teamTag' => $teamTag, 'agentTag' => $agentTag, 'agents' => $agents, 'images' => $array, 'savedrivertag' => $savedrivertag, 'saveteamtag' => $saveteamtag,'main' => $lastbaseurl, 'pricingRule' => $pricingRule]);
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
    //    dd($request->all());
        $task_id = Order::find($id);
        $validator = $this->validator($request->all())->validate();
        $loc_id = 0;
        $cus_id = 0;
        
        $images = [];
        $last = '';
        if (isset($request->file) && count($request->file) > 0) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_'.$folder;
            $files = $request->file('file');
            foreach ($files as $key => $value) {
                $file = $value;
                $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                
                $s3filePath = '/assets/'.$folder.'/' . $file_name;
                $path = Storage::disk('s3')->put($s3filePath, $file,'public');
                array_push($images, $path);
                // $can = Storage::disk('s3')->url('image.png');
                // $last = str_replace('image.png', '', $can);
               
            }
               $last = implode(",", $images);
        }
        if (!isset($request->ids)) {
            $cus = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];
            $customer = Customer::create($cus);
            $cus_id = $customer->id;
        } else {
            $cus_id = $request->ids;
        }
        $order = [
            'customer_id'                => $cus_id,
            'recipient_phone'            => $request->recipient_phone,
            'Recipient_email'            => $request->Recipient_email,
            'task_description'           => $request->task_description,
            'driver_id'                  => isset($request->allocation_type) && $request->allocation_type == 'Manual' ? $request->agent : null,
            'order_type'                 => $request->task_type,
            'order_time'                 => $request->schedule_time,
            'auto_alloction'             => $request->allocation_type,
        ];
        $orders = Order::where('id', $id)->update($order);
         if($last != ''){
            $orderimages = Order::where('id',$id)->update(['images_array' => $last]);
         }
       
         Task::where('order_id',$id)->delete();
        $dep_id = null;
        foreach ($request->task_type_id as $key => $value) {

            if (isset($request->short_name[$key])) {
                $loc = [
                    'short_name' => $request->short_name[$key],
                    'address'    => $request->address[$key],
                    'post_code'  => $request->post_code[$key],
                    'created_by' => $cus_id,
                ];
               $Loction = Location::create($loc);
               $loc_id = $Loction->id;
            } else {
                if($key == 0){
                    $loc_id = $request->old_address_id;
                }else{
                    $loc_id = $request->input('old_address_id'.$key);
                   
                }
                
            }

            $data = [
                'order_id'                   => $id,
                'task_type_id'               => $value,
                'location_id'                => $loc_id,
                'allocation_type'            => $request->allocation_type[$key],            
                'dependent_task_id'          => $dep_id,
            ];
            $task = Task::create($data);
            $dep_id = $task->id;
        }

        if (isset($request->allocation_type) && $request->allocation_type === 'auto') {
            if (isset($request->team_tag)) {
                $task_id->teamtags()->sync($request->team_tag);
            }
            if (isset($request->agent_tag)) {
                $task_id->drivertags()->sync($request->agent_tag);
            }
        }else {
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
            $loction = Location::where('customer_id', $id)->get();
            return response()->json($loction);
        }
    }
}
