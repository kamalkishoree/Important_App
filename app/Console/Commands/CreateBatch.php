<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\TaskController;
use App\Model\Client;
use App\Model\Order;
use Illuminate\Console\Command;
use Config;
use DB;


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
      
            $pickupOrders = Order::with(['task'=>function($o){
                $o->where('task_type_id',1);
            }])->where(['status'=> 'unassigned','request_type'=>'P'])->orderBy('id','desc')->limit('10')->get();
           // \Log::info(json_encode($pickupOrders));
            
           $geoOrders = [];
            foreach($pickupOrders as $k=> $order)
            {
               // \Log::info($order->task[0]->location_id);
                $task = new TaskController();
                $geoId = $task->createRoster($order->task[0]->location_id);                
                $geoOrders[$k] = ['order_id'=>$order->id,'geo_id'=>$geoId];
            }
            \Log::info(json_encode($geoOrders));
            \Log::info('Start Array Sorting asc');
           
            $sort = $this->sortAssociativeArrayByKey($geoOrders,'geo_id','ASC');
        
            \Log::info(json_encode($sort));
            \Log::info('End Array Sorting asc');

            DB::disconnect($database_name);

        }
        
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
