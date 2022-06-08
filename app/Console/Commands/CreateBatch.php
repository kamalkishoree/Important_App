<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\DashBoardController;
use App\Jobs\RosterCreate;
use App\Model\Agent;
use App\Model\AllocationRule;
use App\Model\Client;
use App\Model\Order;
use App\Model\BatchAllocation;
use App\Model\BatchAllocationDetail;
use App\Model\ClientPreference;
use App\Model\DriverGeo;
use App\Model\Location;
use App\Model\orderTemp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Config;
use DB;
use Illuminate\Support\Facades\Auth;

class CreateBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command is used to create batchs of orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Pick only clients which has enable batch allocation feture
        $clients = Client::where(['status'=> 1,'batch_allocation'=>1])->get();
        foreach($clients as $client){


            //Connect client connection for batch allocation
            $database_name = 'db_' . $client->database_name;
            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $database_name,
                'username' => $client->database_username,
                'password' => $client->database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];
            Config::set("database.connections.$database_name", $default);
            DB::setDefaultConnection($database_name);
            
            //Client Prefernces
            $preferences = ClientPreference::where('id', 1)->select('create_batch_hours','maximum_route_per_job','job_consist_of_pickup_or_delivery')->first();

            //Client Allocation Rules
            $allocation = AllocationRule::where('client_id', $client->code)->select('auto_assign_logic','maximum_task_per_person')->first();

            //Fetch Pickup order with tasks
            $pickupOrders = Order::with(['task'=>function($o){
                $o->where('task_type_id',1);
            }])->where(['status'=> 'unassigned','request_type'=>'P'])->orderBy('id','desc')->get();
           //->where('id','164')->orWhere('id','178')->limit(10)
            
            $geoOrders = array();
            foreach($pickupOrders as $k=> $order)
            {
                //Find Geo fence id for every order
                $task = new TaskController();
                $geoId = $task->createRoster($order->task[0]->location_id);  
                if($geoId){  
                    $location = Location::find($order->task[0]->location_id);            
                    $geoOrders[$k] = [
                                    'order_id'=>$order->id,
                                    'task_id'=>$order->task[0]->id,
                                    'geo_id'=>(($geoId)?$geoId:0),
                                    'order_order'=>0,
                                    'task_lat'=>$location->latitude,
                                    'task_long'=>$location->longitude,
                                    ];
                }

            }            
            
            //Sort Geo Fence id wise route 
            $tempOrders = array();
            $sortArray = $this->sortAssociativeArrayByKey($geoOrders,'geo_id','ASC');
            //\Log::info(json_encode($sortArray));
           // dd('hi');
            foreach($sortArray as $sortRec)
            {
                $tempOrders = [
                    'order_id'=>$sortRec['order_id'],
                    'task_id'=>$sortRec['task_id'],
                    'geo_id'=>$sortRec['geo_id'],
                    'order_order'=>0,
                    'task_lat'=>$sortRec['task_lat'],
                    'task_long'=>$sortRec['task_long'],
                    ];

                orderTemp::UpdateOrCreate(['order_id'=>$sortRec['order_id']],$tempOrders);
            }


            $tempOrderRec = orderTemp::select('order_id','task_id','geo_id','task_lat','task_long')->get()->toArray();


            $grpChunks = $this->groupingArray($tempOrderRec);
           
            $i = 0;
            foreach($grpChunks as $sort)
            {
                //\Log::info(json_encode($sort));
                $taskids = array();
                $distancematrixarray = array();

                $distancematrixarray[0][0] = 0;
                $distancematrixarray[0][1] = 0;
                //$taskids[]= 1;
                $ddb  = new DashBoardController();
                foreach($sort as $i => $records)
                {
                    
                    $distancematrixarray[$i+1][0] = $records['task_lat'];
                    $distancematrixarray[$i+1][1] = $records['task_long'];
                    $taskids[] = $records['task_id'];
                }
           
                $points = $distancematrixarray;
                //\Log::info(json_encode($points));
                //\Log::info(json_encode($taskids));
                
                $matrix = $ddb->distanceMatrix($points, implode(',',$taskids));
                $this->otimizeRoutes($matrix,implode(',',$taskids));
           

            $sort = orderTemp::where('geo_id',$records['geo_id'])->select('order_id','task_id','geo_id','task_lat','task_long')->orderBy('order_order','ASC')->get()->toArray();
            

            //Make batch accoring to admin requirments
            $chunks =  array_chunk($sort,$allocation->maximum_task_per_person);
           // \Log::info($chunks);

                    foreach($chunks as $chunk)
                    {
                        $batch_no = time().'_'.++$i;
                        $geo_id = '';
                            foreach($chunk as $detals)
                            {
                                $data = [
                                    'batch_no'   => $batch_no,
                                    'geo_id'     => $detals['geo_id'],
                                    'order_id'   => $detals['order_id'],
                                    'order_time' => Carbon::now()->toDateTimeString(),
                                    'order_type' => 'P', //Pickup
                                    'created_at' => Carbon::now()->toDateTimeString(),
                                ];

                                BatchAllocationDetail::create($data);
                                $geo_id = $detals['geo_id'];
                            }

                                BatchAllocation::create(
                                [
                                    'batch_no' => $batch_no,
                                    'geo_id'   => $geo_id,
                                    'batch_time' => Carbon::now()->toDateTimeString(),
                                    'batch_type' => 'P', //Pickup
                                    'created_at' => Carbon::now()->toDateTimeString(),
                                    ]
                            );

                    }
            }

            //End Coonection
            DB::disconnect($database_name);

        }
        
    }

    function otimizeRoutes($distance_matrix,$taskids)
    {
        $payload = json_encode(array("data" => $distance_matrix));
        //api for getting optimize path
        $url = "https://optimizeroute.royodispatch.com/optimize";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) {
            $taskids = explode(',', $taskids);
            $newtaskidorder = [];
            $newroute = json_decode($result);
            $routecount = count($newroute->data)-1;
            for ($i=1; $i < $routecount; $i++) {
                $taskorder = [
                    'order_order'        => $i
                ];
                $index =  $newroute->data[$i]-1;
                orderTemp::where('task_id', $taskids[$index])->update($taskorder);
                $newtaskidorder[] = $taskids[$index];
            }
        }
    }

    function groupingArray($array)
    {
        $group = array();

        foreach ( $array as $value ) {
            $group[$value['geo_id']][] = $value;
        }

       return $group;

    }


    function sortAssociativeArrayByKey($array, $key, $direction){
        switch ($direction){
            case "ASC":
                usort($array, function ($first, $second) use ($key) {
                    return $first[$key] <=> $second[$key];
                });
                break;
            case "DESC":
                usort($array, function ($first, $second) use ($key) {
                    return $second[$key] <=> $first[$key];
                });
                break;
            default:
                break;
        }
        return $array;
    }

    public function notificationType($allocation,$geo,$notification_time,$agent_id,$batch_no,)
    {
         // $allocation = AllocationRule::where('id', 1)->first();
         switch ($allocation->auto_assign_logic) {
            case 'one_by_one':
                 //this is called when allocation type is one by one
                $this->finalRoster($geo, $notification_time, $agent_id, $orders->id, $customer, $pickup_location, $taskcount, $header, $allocation);
                break;
            case 'send_to_all':
                //this is called when allocation type is send to all
                $this->SendToAll($geo, $notification_time, $agent_id, $orders->id, $customer, $pickup_location, $taskcount, $header, $allocation);
                break;
            case 'round_robin':
                //this is called when allocation type is round robin
                $this->roundRobin($geo, $notification_time, $agent_id, $orders->id, $customer, $pickup_location, $taskcount, $header, $allocation);
                break;
            default:
               //this is called when allocation type is batch wise
                $this->batchWise($geo, $notification_time, $agent_id, $orders->id, $customer, $pickup_location, $taskcount, $header, $allocation);
        }

    }


    public function SendToAll($geo, $notification_time, $agent_id, $batch_id, $customer, $finalLocation, $taskcount, $header, $allocation)
    {
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('database_name', $header['client'][0])->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person??0;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $this->checkTimeDiffrence($notification_time, $beforetime);
        $randem            = rand(11111111, 99999999);
        $data = [];

        if ($type == 'acceptreject') {
            $allcation_type = 'AR';
        } elseif ($type == 'acknowledge') {
            $allcation_type = 'ACK';
        } else {
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

        if (!isset($geo) && !empty($agent_id)) {
            $oneagent = Agent::where('id', $agent_id)->first();
            if(!empty($oneagent->device_token) && $oneagent->is_available == 1){
                $data = [
                    'order_id'            => $orders_id,
                    'driver_id'           => $agent_id,
                    'notification_time'   => $time,
                    'type'                => $allcation_type,
                    'client_code'         => $auth->code,
                    'created_at'          => Carbon::now()->toDateTimeString(),
                    'updated_at'          => Carbon::now()->toDateTimeString(),
                    'device_type'         => $oneagent->device_type,
                    'device_token'        => $oneagent->device_token,
                    'detail_id'           => $randem,
                ];
                $this->dispatch(new RosterCreate($data, $extraData));
            }
        } else {
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function ($o) use ($cash_at_hand, $date) {
                    $o->whereRaw("(select COALESCE(SUM(cash_to_be_collected),0) from orders where orders.driver_id=agents.id and status='completed') - (select COALESCE(SUM(driver_cost),0) from orders where orders.driver_id=agents.id and status='completed') + (select COALESCE(SUM(cr),0) as sum from payments where payments.driver_id=agents.id) - (select COALESCE(SUM(dr),0) as sum from payments where payments.driver_id=agents.id) - ((select COALESCE(balance,0) as sum from wallets where wallets.holder_id=agents.id)/100) + (select COALESCE(SUM(amount),0) from agent_payouts where agent_payouts.agent_id=agents.id and agent_payouts.status=0) < ".$cash_at_hand)->orderBy('id', 'DESC')->with(['logs','order'=> function ($f) use ($date) {
                        $f->whereDate('order_time', $date)->with('task');
                    }]);
                }])->get();


            for ($i = 1; $i <= $try; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {
                    if (!empty($geoitem->agent->device_token) && $geoitem->agent->is_available == 1) {
                        $datas = [
                            'order_id'            => $orders_id,
                            'driver_id'           => $geoitem->driver_id,
                            'notification_time'   => $time,
                            'type'                => $allcation_type,
                            'client_code'         => $auth->code,
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $geoitem->agent->device_type,
                            'device_token'        => $geoitem->agent->device_token,
                            'detail_id'           => $randem,

                        ];
                        array_push($data, $datas);
                        if ($allcation_type == 'N' && 'ACK') {
                            Order::where('id', $orders_id)->update(['driver_id'=>$geoitem->driver_id]);
                            break;
                        }
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

            // print_r($data);
            //  die;
            //die('hello');
        }
    }


}
