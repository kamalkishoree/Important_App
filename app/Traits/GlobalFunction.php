<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Log;
use App\Model\{ChatSocket,Client};
use Illuminate\Support\Facades\Config;


trait GlobalFunction{
    

   
    public static function socketDropDown()
    {
        $chatSocket= ChatSocket::where('status', 1)->get();
        return $chatSocket;
    }

    public static function checkDbStat($id)
    {
        try {
            $client = Client::find($id);
        
            $schemaName = 'db_' . $client->database_name;
            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');

            $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => $database_host,
            'port' => $database_port,
            'database' => $schemaName,
            'username' => $database_username,
            'password' => $database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];
    
            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            $return['schemaName'] =  $schemaName;
            $return['clientData'] =  $client;

            return $return;
        } catch (\Throwable $th) {
            $return['schemaName'] =  '';
            $return['clientData'] =  [];
            return $return;
        }
    
    }

    public function AgentOrderAnalytics($data,$type){
        if($data){
            $order_assigned = $order_unassigned  = $order_completed = $order_amount = 0;
            $statusArr = [];
           foreach($data as $order){
             
             if($order->status == 'assigned'){
                $order_assigned += 1; 
                $order_amount   += $order->order_cost;
             }else if($order->status == 'unassigned'){
                $order_unassigned += 1;
                $order_amount   += $order->order_cost;
             }
             else if($order->status == 'completed'){
                $order_completed += 1;
                $order_amount   += $order->order_cost;
             }
           }

           $statusArr = ['assigned'=>$order_assigned,'unassigned'=>$order_unassigned,'completed'=>$order_completed,'order_amount'=>$order_amount,$type=>$data->count()];
           return json_encode($statusArr,true);
        }
    }
   
   
   

}