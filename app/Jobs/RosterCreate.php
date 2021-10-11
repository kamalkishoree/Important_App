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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('roster-data-fun-----');
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

            // config(["database.connections.mysql.database" => null]);
         
            Config::set("database.connections.$schemaName", $default);
        //    Log::info('mesooooo2');
            config(["database.connections.mysql.database" => $schemaName]);
            Log::info('roster-create-mm');
           Log::info($this->data);
           Log::info('roster-create-mm');
            DB::connection($schemaName)->table('rosters')->insert($this->data);
        //    Log::info('mesooooo4');
            DB::connection($schemaName)->table('roster_details')->insert($this->extraData);
        //    Log::info('mesooooo5');
            DB::disconnect($schemaName);
            //Roster::insert($this->data);
        //    Log::info($this->data);
        //    Log::info($this->extraData);
            Roster::create([
                'type'  => 'extra',
                'status'=> 10
            ]);
            Log::info('create roster');
        } catch (Exception $ex) {
            Log::info($exception->getMessage());
           return $ex->getMessage();
        }
       
    }

    public function failed(\Throwable $exception)
    {
        // Log failure
        
        
        Log::info('error roster');
        Log::info($exception->getMessage());
       


    }
}
