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
use Exception;
use Kawankoding\Fcm\Fcm;

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
       Log::info('message');
        
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
       Log::info('getData');
        
        $schemaName       = 'royodelivery_db';
        $date             =  Carbon::now()->toDateTimeString();
        $get              =  DB::connection($schemaName)->table('rosters')->where('notification_time', '<=', $date)->where('status',0)->leftJoin('roster_details', 'rosters.detail_id', '=', 'roster_details.unique_id')->select('rosters.*', 'roster_details.customer_name', 'roster_details.customer_phone_number',
        'roster_details.short_name','roster_details.address','roster_details.lat','roster_details.long','roster_details.task_count')->get();
        $newget           = $get->pluck('id');

     ///   Log::info($newget);
        DB::connection($schemaName)->table('rosters')->where('status',10)->delete();
        if(count($get) > 0){
            Log::info('rosters update-99-');
            DB::connection($schemaName)->table('rosters')->whereIn('id',$newget)->delete();
            // DB::connection($schemaName)->table('rosters')->whereIn('id',$newget)->update(['status'=>1]);
            
            $this->sendnotification($get);
        }else{
            $this->extraTime($schemaName);
            Log::info('Empty Roaster');
          
        }
        
        return;
               
       
        
    }

    public function sendnotification($recipients)
    {
        try {
           
        Log::info('sendnotificationlistener');
        
        $array = json_decode(json_encode($recipients), true);
        
    
        foreach($array as $item){

            if(isset($item['device_token']) && !empty($item['device_token'])){
               
                $item['title']     = 'Pickup Request';
                $item['body']      = 'Check All Details For This Request In App';
                $item['channelId']      = 'Royo Delivery';
                $new = [];
                array_push($new,$item['device_token']);

                $clientRecord = Client::where('code', $item['client_code'])->first();
                $this->seperate_connection('db_'.$clientRecord->database_name);
                $client_preferences = DB::connection('db_'.$clientRecord->database_name)->table('client_preferences')->where('client_id', $item['client_code'])->first();

                if(isset($new)){
                    try{
                       
                        $fcm_server_key = !empty($client_preferences->fcm_server_key)? $client_preferences->fcm_server_key : config('laravel-fcm.server_key');
                        
                        $fcmObj = new Fcm($fcm_server_key);
                        $fcm_store = $fcmObj->to($new) // $recipients must an array
                                        ->priority('high')
                                        ->timeToLive(0)
                                        ->data($item)
                                        ->notification([
                                            'title'     => 'Pickup Request',
                                            'body'      => 'Check All Details For This Request In App',
                                            'sound'     => 'notification.mp3',
                                            'channelId' => 'Royo Delivery'
                                        ])
                                        ->send();
                                        
                    
                    }
                    catch(Exception $e){
                        Log::info($e->getMessage());
                    }
                
                }
            }
            
            
        }

        sleep(5);
        $this->getData();
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
            
        }
       
    }

    public function extraTime($schemaName)
    {
    //    Log::info('extraTime');

        $check = DB::connection($schemaName)->table('rosters')->get();

        if(count($check) > 0){
            sleep(5);
            $this->getData();
        }else{
            return;
        }
    }

    public function seperate_connection($schemaName){
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
    }

}
