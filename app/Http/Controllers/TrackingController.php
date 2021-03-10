<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Config;

class TrackingController extends Controller
{
    
    public function OrderTracking($user,$id)
    {
        $status = $this->connection($user,$id);

        if($status == 'connected'){
            return view('tracking/tracking');
        }else{
            
        }
        
    }

    public function OrderFeedback($user,$id)
    {
        $status = $this->connection($user,$id);
        
        if($status == 'connected'){
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
            
            return  $status = 'connected';
             
        } else {

            return  $status = 'failed';
        
        }
        
    }
}
