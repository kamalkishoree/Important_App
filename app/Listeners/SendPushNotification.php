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
        $recipients = [];
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

                $get =  DB::connection($schemaName)->table('rosters')->where('notification_time', '<=', $date)->get();
                
                foreach($get as $item){
                    if(isset($item->device_token))
                    array_push($recipients,$item->device_token);
                }
                
                $this->sendnotification($recipients);

                DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
        
        
    }

    public function sendnotification($recipients)
    {
        // dd($recipients);
        Log::info('good man');
        if(isset($recipients)){
            fcm()
            ->to($recipients) // $recipients must an array
            ->priority('high')
            ->timeToLive(0)
            ->data([
                'title' => 'Test FCM',
                'body' =>  'This is a test of FCM',
            ])
            ->notification([
                'title' => 'Pickup Request',
                'body' =>  'Accecpt Request Task From App',
            ])
            ->send();
        }
    }
}
