<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Config;

class TrackingController extends Controller
{
    
    public function OrderTracking($user,$id)
    {
        $respnse = $this->connection($user,$id);
        
        if($respnse['status'] == 'connected'){

            $order   = DB::connection($respnse['database'])->table('orders')->where('unique_id',$id)->leftJoin('agents', 'orders.driver_id', '=', 'agents.id')
            ->select('orders.*', 'agents.name', 'agents.profile_picture','agents.phone_number')->first();
            
            $tasks   = DB::connection($respnse['database'])->table('tasks')->where('order_id', $order->id)->leftJoin('locations', 'tasks.location_id', '=', 'locations.id')
            ->select('tasks.*', 'locations.latitude', 'locations.longitude','locations.short_name','locations.address')->get();


            return view('tracking/tracking',compact('tasks','order'));

        }else{

            
        }
        
    }

    public function OrderFeedback($user,$id)
    {
        $respnse = $this->connection($user,$id);
        
        if($respnse['status'] == 'connected'){

            $order   = DB::connection($respnse['database'])->table('orders')->where('unique_id',$id)->first();
            
            $tasks   = DB::connection($respnse['database'])->table('tasks')->where('order_id', $order->id)->leftJoin('locations', 'tasks.location_id', '=', 'locations.id')
            ->select('tasks.*', 'locations.latitude', 'locations.longitude','locations.short_name','locations.address')->get();


            return view('tracking/tracking',compact('tasks','order'));

        }else{

            
        }
        if($respnse['status'] == 'connected'){
            return view('tracking/feedback');
        }else{
            
        }

        return view('tracking/feedback');
    }

    public function connection($user,$id)
    {
        
        $client = Client::where('code',$user)->first();

        if(isset($client->database_name))
        {
            $database_name = 'db_' . $client->database_name;

            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $database_name,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];

            Config::set("database.connections.$database_name", $default);
            
            return  $respnse = ['status' => 'connected','database'=>$database_name];
             
        } else {

            return  $respnse = ['status' => 'failed'];
        
        }
        
    }
}
