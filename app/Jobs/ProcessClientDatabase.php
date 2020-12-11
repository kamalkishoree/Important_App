<?php

namespace App\Jobs;

use App\Model\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;

class ProcessClientDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('id', $this->client_id)->first(['name', 'email', 'password', 'phone_number', 'password', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code'])->toarray();
        try {
           
            $schemaName = 'db_' . $client['database_name'] ?: config("database.connections.mysql.database");
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

            $query = "CREATE DATABASE $schemaName;";

            DB::statement($query);


            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            Artisan::call('migrate', ['--database' => $schemaName]);
            Artisan::call('db:seed', ['--class' => 'CountriesTableSeeder', '--database' => $schemaName]);
            Artisan::call('db:seed', ['--class' => 'CurrenciesTableSeeder', '--database' => $schemaName]);
            Artisan::call('db:seed', ['--class' => 'NotificationSeeder', '--database' => $schemaName]);
            Artisan::call('db:seed', ['--class' => 'PlanSeeder', '--database' => $schemaName]);
            DB::connection($schemaName)->table('clients')->insert($client);
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
    }
}
