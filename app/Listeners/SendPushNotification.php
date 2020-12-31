<?php

namespace App\Listeners;

use App\Events\PushNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
Use Log;
use Carbon\Carbon;
use App\Model\Roster;
use App\Model\Client;
use Config;
use Illuminate\Support\Facades\DB;

class SendPushNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PushNotification  $event
     * @return void
     */
    public function handle(PushNotification $event)
    {
        //Log::info('message');
        
        $date =  Carbon::now()->toDateTimeString();
        try {
           
                $schemaName = 'royodelivery_db';
                $default = [
                    'driver' => env('DB_CONNECTION', 'mysql'),
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'database' => $schemaName,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => false,
                    'engine' => null
                ];

                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);

                $this->getData();
                

                DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
        
        
    }

    public function getData()
    {
        $recipients       = [];
        $updateStatus     = [];
        $schemaName = 'royodelivery_db';
        $date =  Carbon::now()->toDateTimeString();
        $get =  DB::connection($schemaName)->table('rosters')->where('notification_time', '<=', $date)->where('status',0)->leftJoin('roster_details', 'rosters.detail_id', '=', 'roster_details.unique_id')->get()->toArray();
        
                DB::connection($schemaName)->table('rosters')->where('status',1)->delete();
        if(isset($get)){
            foreach($get as $item){
                array_push($updateStatus,$item->id);
            }
            DB::connection($schemaName)->table('rosters')->whereIn('id',$updateStatus)->update(['status'=>1]);
            $this->sendnotification($get);
        }else{
            return;
        }        
        
    }

    public function sendnotification($recipients)
    {
        // print_r($recipients->toArray());
        // die();
        $array = json_decode(json_encode($recipients), true);
       
    
        foreach($array as $item){
                $item['title'] = 'Pickup Request';
                $item['body']  = 'Check All Details For This Request In App';
                $new = [];
                array_push($new,$item['device_token']);
            if(isset($new)){
                fcm()
                ->to($new) // $recipients must an array
                ->priority('high')
                ->timeToLive(0)
                ->data($item)
                // ->notification([
                //     'title' => 'Pickup Request',
                //     'body' =>  'Check All Details For This Request In App',
                // ])
                ->send();
            }
        }
        
        // $this->getData();
       
       
    }
}
