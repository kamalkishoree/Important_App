<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ClientNotificationController;
use Log;
use Omnipay\Omnipay;
use App\Model\Roster;
use Carbon\Carbon;
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout, AgentBankDetail,AgentCashCollectPop,Task, Users};
use Config;
use Illuminate\Support\Facades\DB;
use App\Traits\agentDebitThresholdAmount;


class DriverThresholdForDay extends Command
{
     use agentDebitThresholdAmount;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Thresholdforday:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
       
        try {
            $clients = Client::where('status', 1)->get();
            foreach ($clients as $key => $client) {
                //Connect client connection 
                $database_name  = 'db_' . $client->database_name;
                $header         = $client->database_name;
            
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
               
                $threshold = ClientPreference::with('currency')->where('id', 1)->first();
               
                if($threshold->is_threshold == 1 && !empty($threshold->threshold_data)){
                    $threshold_data = json_decode($threshold->threshold_data,true);
                    $this->AgentDebitThresholdAmount($threshold_data,1); // 1 for day
                }
                \DB::disconnect($database_name);
            }
           // \Log::info($orders);
        }catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 0;
    }

      
        
}
