<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Godpanel\DashBoardController;
use App\Model\AllocationRule;
use App\Model\Client;
use App\Model\Order;
use App\Model\BatchAllocation;
use App\Model\BatchAllocationDetail;
use App\Model\ClientPreference;
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
            $allocation = AllocationRule::where('client_id', $client->code)->select('maximum_task_per_person')->first();

            //Fetch Pickup order with tasks
            $pickupOrders = Order::with(['task'=>function($o){
                $o->where('task_type_id',1);
            }])->where(['status'=> 'unassigned','request_type'=>'P'])->orderBy('id','desc')->get();
           
            
           $geoOrders = [];
            foreach($pickupOrders as $k=> $order)
            {
                //Find Geo fence id for every order
                $task = new TaskController();
                $geoId = $task->createRoster($order->task[0]->location_id);                
                $geoOrders[$k] = ['order_id'=>$order->id,'geo_id'=>$geoId];
            }
            $ddb  = new DashBoardController();
            $pointarray[0][1] = '11.000210121'; 
            $pointarray[0][2] = '17.111210121'; 
            $taskids = 
            $matrix = $ddb->distanceMatrix($pointarray, $taskids);
            

            //Sort Geo Fence id wise route
            $sort = $this->sortAssociativeArrayByKey($geoOrders,'geo_id','ASC');
        
            

            //Now find  Shortest Path according to geo ids



            $grpChunks = $this->groupingArray($sort);
            //\Log::info($grpChunks);

            $i = 0;
            foreach($grpChunks as $sort)
            {
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


}
