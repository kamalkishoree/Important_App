<?php

namespace App\Jobs;

use App\Model\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Carbon\Carbon;
use App\Model\Roster;
use App\Model\Client;
use App\Model\DriverGeo;
use App\Model\Order;
use Config;
use Illuminate\Support\Facades\DB;
use App\Jobs\RosterCreate;
use Illuminate\Support\Arr;

class scheduleNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data       = $data;
        

        try {
            $databaseName = $this->data['database']['database_name'];

            $schemaName = 'db_'.$databaseName;

            $default = [
                'driver'        => env('DB_CONNECTION', 'mysql'),
                'host'          => env('DB_HOST'),
                'port'          => env('DB_PORT'),
                'database'      => $schemaName,
                'username'      => env('DB_USERNAME'),
                'password'      => env('DB_PASSWORD'),
                'charset'       => 'utf8mb4',
                'collation'     => 'utf8mb4_unicode_ci',
                'prefix'        => '',
                'prefix_indexes'=> true,
                'strict'        => false,
                'engine'        => null
            ];

            // config(["database.connections.mysql.database" => null]);



            Config::set("database.connections.$schemaName", $default);
            //config(["database.connections.mysql.database" => $schemaName]);
            
           DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
        $this->schemaName = $schemaName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

       

        switch ($this->data['allocation']['auto_assign_logic']) {
            case 'one_by_one':
                //this is called when allocation type is one by one
                $this->OneByOne($this->data);
                break;
            case 'send_to_all':
                //this is called when allocation type is send to all
                $this->SendToAll($this->data);
                break;
            case 'round_robin':
                //this is called when allocation type is round robin
                $this->roundRobin($this->data);
                break;
            default:
                //this is called when allocation type is batch wise 
                $this->batchWise($this->data);
        }

       
    }


    public function basicSetup($schemaName)
    {
       
        // DB::connection($schemaName)->table('clients')->where('database',$this->data['database']['database_name'])
        // ->with(['getAllocation', 'getPreference'])->first();

        
    }


    public function OneByOne($dataget)
    {

        
        // print($geo);
        // print($notification_time);
        // print($agent_id);
        // print($orders_id);
        // die;
        $allcation_type = 'AR';
        $date = \Carbon\Carbon::today();
        //$auth = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference'])->first();
       
        $expriedate = (int)$dataget['database']['getAllocation']['request_expiry'];
        $beforetime = (int)$dataget['database']['getAllocation']['start_before_task_time'];
        $maxsize    = (int)$dataget['database']['getAllocation']['maximum_batch_size'];
        $type       = $dataget['database']['getPreference']['acknowledgement_type'];
        $try        = $dataget['database']['getAllocation']['number_of_retries'];
        $time       = $dataget['notification_time'];
        $randem     = rand(11111111, 99999999);

        if ($type !== 'acceptreject') {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $dataget['customer']['name'],
            'customer_phone_number'    => $dataget['customer']['phone_number'],
            'short_name'               => $dataget['finalLocation']['short_name'],
            'address'                  => $dataget['finalLocation']['address'],
            'lat'                      => $dataget['finalLocation']['latitude'],
            'long'                     => $dataget['finalLocation']['longitude'],
            'task_count'               => $dataget['taskcount'],
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

         $geo = $dataget['geo'];
        if (!isset($geo)) {
            $oneagent = Agent::where('id', $dataget['agent_id'])->first();
            
            $data = [
                'order_id'            => $dataget['orders_id'],
                'driver_id'           => $dataget['agent_id'],
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => $dataget['database']['code'],
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($data, $extraData);
           
            
        } else {

           
            
            
           
            
            
            $unit              = $dataget['database']['getPreference']['distance_unit'];
            $try               = $dataget['database']['getAllocation']['number_of_retries'];
            $cash_at_hand      = $dataget['database']['getAllocation']['maximum_cash_at_hand_per_person'];
            $max_redius        = $dataget['database']['getAllocation']['maximum_radius'];
            $max_task          = $dataget['database']['getAllocation']['maximum_batch_size'];
            

            $dummyentry = [];
            $all        = [];
            $extra      = [];
            $remening   = [];

            $getgeo =  DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs','order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get();
           
            $totalcount = $getgeo->count();
            
            $orders = Order::where('driver_id', '!=', null)->whereDate('created_at', $date)->groupBy('driver_id')->get('driver_id');

            $allreadytaken = [];
            foreach ($orders as $ids) {
                array_push($allreadytaken, $ids->driver_id);
            }
            // print_r($allreadytaken);
            // die;
            $counter = 0;
            $data = [];
            for ($i = 1; $i <= $try; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {


                    if (in_array($geoitem->driver_id, $allreadytaken)) {
                        $extra = [
                            'id' => $geoitem->driver_id,
                            'device_type' => $geoitem->agent->device_type, 'device_token' => $geoitem->agent->device_token
                        ];
                        array_push($remening, $extra);
                    } else {


                        $data = [
                            'order_id'            => $dataget['orders_id'],
                            'driver_id'           => $geoitem->driver_id,
                            'notification_time'   => $time,
                            'type'                => $allcation_type,
                            'client_code'         => $dataget['database']['code'],
                            'created_at'          => Carbon::now()->toDateTimeString(),
                            'updated_at'          => Carbon::now()->toDateTimeString(),
                            'device_type'         => $geoitem->agent->device_type,
                            'device_token'        => $geoitem->agent->device_token,
                            'detail_id'           => $randem,

                        ];
                        if (count($dummyentry) < 1) {
                            array_push($dummyentry, $data);
                           
                        }
                        $time = Carbon::parse($time)
                            ->addSeconds($expriedate + 3)
                            ->format('Y-m-d H:i:s');
                        array_push($all, $data);
                        $counter++;
                    }

                    if ($allcation_type == 'N' && count($all) > 0) {

                        Order::where('id',$dataget['orders_id'])->update(['driver_id'=>$geoitem->driver_id]);

                        break;
                    }
                }
                
                foreach($remening as $key =>  $rem){
                    $data = [
                        'order_id'            => $dataget['orders_id'],
                        'driver_id'           => $rem['id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => $dataget['database']['code'],
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $rem['device_type'],
                        'device_token'        => $rem['device_token'],
                        'detail_id'           => $randem,
                    ];

                        $time = Carbon::parse($time)
                        ->addSeconds($expriedate + 3)
                        ->format('Y-m-d H:i:s');

                        if (count($dummyentry) < 1) {
                            array_push($dummyentry, $data);
                        }
                        array_push($all, $data);
                        if ($allcation_type == 'N' && count($all) > 0) {
                            Order::where('id',$dataget['orders_id'])->update(['driver_id'=>$remening[$i]['id']]);
    
                            break;
                        }
                }
                $remening = [];
                if ($allcation_type == 'N' && count($all) > 0) {

                    break;
                }
            }
           
            // print_r($all);
            // print()
            DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($all, $extraData);
            //$this->dispatch(new RosterCreate());
            
            
        }
    }

    public function SendToAll($dataget)
    {
       // Log::info($dataget);
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', $dataget['database']['code'])->with(['getAllocation', 'getPreference'])->first();
        Log::info($auth);
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $dataget['notification_time'];
        $randem            = rand(11111111, 99999999);
        $data = [];
        if ($type != 'acceptreject') {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $dataget['customer']['name'],
            'customer_phone_number'    => $dataget['customer']['phone_number'],
            'short_name'               => $dataget['finalLocation']['short_name'],
            'address'                  => $dataget['finalLocation']['address'],
            'lat'                      => $dataget['finalLocation']['latitude'],
            'long'                     => $dataget['finalLocation']['longitude'],
            'task_count'               => $dataget['taskcount'],
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        $geo = $dataget['geo'];
        if (!isset($geo)) {
            $oneagent = Agent::where('id', $dataget['agent_id'])->first();
            $data = [
                'order_id'            => $dataget['orders_id'],
                'driver_id'           => $dataget['agent_id'],
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => $auth->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
           //DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($data, $extraData);
            //$this->dispatch(new RosterCreate());
            
        } else {

            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs','order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get();
           

            for ($i = 1; $i <= $try; $i++) {
                foreach ($getgeo as $key =>  $geoitem) {

                    $datas = [
                        'order_id'            => $dataget['orders_id'],
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
                    if ($allcation_type == 'N') {
                        Order::where('id',$dataget['orders_id'])->update(['driver_id'=>$geoitem->driver_id]);
                        break;
                    }
                }
                $time = Carbon::parse($time)
                        ->addSeconds($expriedate + 3)
                        ->format('Y-m-d H:i:s');
                if ($allcation_type == 'N') {

                    break;
                }
            }
           
            //DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($data, $extraData);
            //$this->dispatch(new RosterCreate($data, $extraData));
           
            // print_r($data);
            //  die;
            //die('hello');
        }
    }

    public function roundRobin($dataget)
    {
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', $dataget['database']['code'])->with(['getAllocation', 'getPreference'])->first();
        
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $dataget['notification_time'];
        $randem            = rand(11111111, 99999999);
        $data = [];

        if ($type != 'acceptreject') {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $dataget['customer']['name'],
            'customer_phone_number'    => $dataget['customer']['phone_number'],
            'short_name'               => $dataget['finalLocation']['short_name'],
            'address'                  => $dataget['finalLocation']['address'],
            'lat'                      => $dataget['finalLocation']['latitude'],
            'long'                     => $dataget['finalLocation']['longitude'],
            'task_count'               => $dataget['taskcount'],
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];
        $geo = $dataget['geo'];
        if (!isset($geo)) {
            $oneagent = Agent::where('id', $dataget['agent_id'])->first();
            $data = [
                'order_id'            => $dataget['orders_id'],
                'driver_id'           => $dataget['agent_id'],
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => $auth->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($data, $extraData);
        } else {

            //$getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs','order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get()->toArray();
           
           $distenseResult = $this->roundCalculation($getgeo,$dataget['finalLocation'],$unit,$max_redius,$max_task);
           
           
            for ($i = 1; $i <= $try; $i++) {
                
                foreach ($distenseResult as $key =>  $geoitem) {

                    $datas = [
                        'order_id'            => $dataget['orders_id'],
                        'driver_id'           => $geoitem['driver_id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => $auth->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $geoitem['device_type'],
                        'device_token'        => $geoitem['device_token'],
                        'detail_id'           => $randem,
                    ];
                   
                    $time = Carbon::parse($time)
                    ->addSeconds($expriedate)
                    ->format('Y-m-d H:i:s');

                    array_push($data, $datas);
                    if ($allcation_type == 'N') {

                        break;
                    }
                }

                $time = Carbon::parse($time)
                    ->addSeconds($expriedate +10)
                    ->format('Y-m-d H:i:s');


                if ($allcation_type == 'N') {

                    break;
                }
            }
            DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($data, $extraData);
            
            
        }
    }



    public function roundCalculation($getgeo,$finalLocation,$unit,$max_redius,$max_task)
    {
        
        
        $extraarray    = [];
        
        foreach($getgeo as $item){
            
                    $count = isset($item['agent']['order']) ? count($item['agent']['order']):0;
                    
                    if($max_task > $count){
                       
                            $data = [
                                'driver_id'    =>  $item['agent']['id'],
                                'device_type'  =>  $item['agent']['device_type'], 
                                'device_token' =>  $item['agent']['device_token'],
                                'task_count'   =>  $count,
                            ];
                           
                            array_push($extraarray,$data); 
                    }
                        
                           
        }
        
        
        $allsort = array_values(Arr::sort($extraarray, function ($value) {
            return $value['task_count'];
        }));
       
        return $allsort;
    }


    public function batchWise($dataget)
    {
        $allcation_type    = 'AR';
        $date              = \Carbon\Carbon::today();
        $auth              = Client::where('code', $dataget['database']['code'])->with(['getAllocation', 'getPreference'])->first();
        $expriedate        = (int)$auth->getAllocation->request_expiry;
        $beforetime        = (int)$auth->getAllocation->start_before_task_time;
        $maxsize           = (int)$auth->getAllocation->maximum_batch_size;
        $type              = $auth->getPreference->acknowledgement_type;
        $unit              = $auth->getPreference->distance_unit;
        $try               = $auth->getAllocation->number_of_retries;
        $cash_at_hand      = $auth->getAllocation->maximum_cash_at_hand_per_person;
        $max_redius        = $auth->getAllocation->maximum_radius;
        $max_task          = $auth->getAllocation->maximum_batch_size;
        $time              = $dataget['notification_time'];
        $randem            = rand(11111111, 99999999);
        $data = [];


        if ($type != 'acceptreject') {
            $allcation_type = 'N';
        }

        $extraData = [
            'customer_name'            => $dataget['customer']['name'],
            'customer_phone_number'    => $dataget['customer']['phone_number'],
            'short_name'               => $dataget['finalLocation']['short_name'],
            'address'                  => $dataget['finalLocation']['address'],
            'lat'                      => $dataget['finalLocation']['latitude'],
            'long'                     => $dataget['finalLocation']['longitude'],
            'task_count'               => $dataget['taskcount'],
            'unique_id'                => $randem,
            'created_at'               => Carbon::now()->toDateTimeString(),
            'updated_at'               => Carbon::now()->toDateTimeString(),
        ];

        $geo = $dataget['geo'];
        if (!isset($geo)) {
            $oneagent = Agent::where('id', $dataget['agent_id'])->first();
            $data = [               
                'order_id'            => $dataget['orders_id'],
                'driver_id'           => $dataget['agent_id'],
                'notification_time'   => $time,
                'type'                => $allcation_type,
                'client_code'         => $auth->code,
                'created_at'          => Carbon::now()->toDateTimeString(),
                'updated_at'          => Carbon::now()->toDateTimeString(),
                'device_type'         => $oneagent->device_type,
                'device_token'        => $oneagent->device_token,
                'detail_id'           => $randem,
            ];
            DB::disconnect('db_'.$dataget['database']['code']);
            RosterCreate::dispatch($data, $extraData);
           // $this->dispatch(new RosterCreate($data, $extraData));
            
        } else {

            //$getgeo = DriverGeo::where('geo_id', $geo)->with('agent')->get('driver_id');
            $getgeo = DriverGeo::where('geo_id', $geo)->with([
                'agent'=> function($o) use ($cash_at_hand,$date){
                    $o->where('cash_at_hand','<',$cash_at_hand)->orderBy('id','DESC')->with(['logs' => function($g){
                        $g->orderBy('id','DESC');}
                        ,'order'=> function($f) use ($date){
                        $f->whereDate('order_time',$date)->with('task');
                    }]);
                }])->get()->toArray();
           
           $distenseResult = $this->haversineGreatCircleDistance($getgeo,$dataget['finalLocation'],$unit,$max_redius,$max_task);
           
          
           
            for ($i = 1; $i <= $try; $i++) {
                $counter = 0;
                foreach ($distenseResult as $key =>  $geoitem) {

                    $datas = [
                        'order_id'            => $dataget['orders_id'],
                        'driver_id'           => $geoitem['driver_id'],
                        'notification_time'   => $time,
                        'type'                => $allcation_type,
                        'client_code'         => $auth->code,
                        'created_at'          => Carbon::now()->toDateTimeString(),
                        'updated_at'          => Carbon::now()->toDateTimeString(),
                        'device_type'         => $geoitem['device_type'],
                        'device_token'        => $geoitem['device_token'],
                        'detail_id'           => $randem,
                    ];
                    $counter++;
                    if($counter == $maxsize){
                        $time = Carbon::parse($time)
                        ->addSeconds($expriedate)
                        ->format('Y-m-d H:i:s');

                        $counter = 0;
                    }
                    array_push($data, $datas);
                    if ($allcation_type == 'N') {

                        break;
                    }
                }
                $time = Carbon::parse($time)
                ->addSeconds($expriedate + 10)
                ->format('Y-m-d H:i:s');

                if ($allcation_type == 'N') {

                    break;
                }
            }
            DB::disconnect('db_'.$dataget['database']['code']);
             RosterCreate::dispatch($data, $extraData);
            //$this->dispatch(new RosterCreate($data, $extraData));
            
        }
    }

    function haversineGreatCircleDistance($getgeo,$finalLocation,$unit,$max_redius,$max_task)
    {
        //$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, 
        // convert from degrees to radians

        $earthRadius = 6371;  // earth radius in km
        $latitudeFrom  = $finalLocation->latitude;
        $longitudeFrom = $finalLocation->longitude;
        $lastarray     = [];
        $extraarray    = [];
        foreach($getgeo as $item){
            $latitudeTo  = $item['agent']['logs']['lat'];
            $longitudeTo = $item['agent']['logs']['long'];
            
            if(isset($latitudeFrom) && isset($latitudeFrom) && isset($latitudeTo) && isset($longitudeTo)){

                $latFrom = deg2rad($latitudeFrom);
                $lonFrom = deg2rad($longitudeFrom);
                $latTo   = deg2rad($latitudeTo);
                $lonTo   = deg2rad($longitudeTo);
          
                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;
          
                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
             
                $final = round($angle * $earthRadius);
                $count = isset($item['agent']['order']) ? count($item['agent']['order']):0;
                if($unit == 'metric'){
                    
                    if($final <= $max_redius && $max_task > $count ){
                        

                        $data = [
                            'driver_id'    =>  $item['agent']['logs']['agent_id'],
                            'device_type'  =>  $item['agent']['device_type'], 
                            'device_token' =>  $item['agent']['device_token'],
                            'distance'     =>  $final
                        ];
                        array_push($extraarray,$data); 
                    }

                }else{
                    
                    if($final <= $max_redius && $max_task > $count ){
                        $data = [
                            'driver_id'    =>  $item['agent']['logs']['agent_id'],
                            'devide_type'  =>  $item['agent']['device_type'], 
                            'device_token' =>  $item['agent']['device_token'],
                            'distance'     =>  round($final * 0.6214)
                        ];
                        array_push($extraarray,$data);
                        
                    }
                    
                    
                }
                
               
                

            }
            

        }
          
        $allsort = array_values(Arr::sort($extraarray, function ($value) {
            return $value['distance'];
        }));
       
        return $allsort;
    }




}
