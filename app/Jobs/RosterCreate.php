<?php

namespace App\Jobs;

use App\Model\Roster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Config;
Use Log;
use Carbon\Carbon;

class RosterCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $extraData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$extraData)
    {
        $this->data      = $data;
        $this->extraData = $extraData;
        \Log::info("construct");
    }

    public function handle()
    {
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

            DB::connection($schemaName)->table('rosters')->insert($this->data);
            
            \Log::info('check roster entry');
            // Log::info(DB::connection($schemaName)->table('rosters')->get());

            DB::connection($schemaName)->table('roster_details')->insert($this->extraData);
       
            DB::disconnect($schemaName);
           
            Roster::create([
                'type'  => 'extra',
                'status'=> 10
            ]);
            $date   =  Carbon::now()->toDateTimeString();
         
        } catch (\Exception $ex) {
            \Log::info($ex->getMessage());
           return $ex->getMessage();
        }

    }

    public function failed(\Throwable $exception)
    {
        // Log failure
        Log::info('error roster :'.$exception->getMessage());
    }
}
