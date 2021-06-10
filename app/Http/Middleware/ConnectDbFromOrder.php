<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Model\Client;
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
class ConnectDbFromOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    

    public function handle($request, Closure $next)
    {

        config(['auth.guards.api.provider' => 'agents']);

        $database_name = $database = 'royodelivery_db';

        $header = $request->header();
        if (array_key_exists("shortCode", $header)){
            $shortCode =  $header['shortCode'][0];
        }
        if (array_key_exists("personal_access_token_v1", $header)){
            $personal_access_token_v1 =  $header['personal_access_token_v1'][0];
        }
        //$client = Cache::get($database);

        $client = Client::where('is_deleted', 0)->where('code', $shortCode)->first(['id', 'name', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked']);
        if (!$client) {
            return response()->json([
                'error' => 'Company not found',
                'message' => 'Invalid short code. Please enter a valid short code.'], 404);
        }
        if ($client->is_blocked == 1) {
            return response()->json([
                'error' => 'Blocked Company',
                'message' => 'Company has been blocked. Please contact administration.'], 404);
        }

      
        if (isset($client)) {
            $database_name =  'db_'.$client['database_name'];
            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST','127.0.0.1');
            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT','3306');
            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME','royodelivery_db');
            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD','');
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
            Config::set("client_connected", true);
          //  Config::set("client_data", $client);
            DB::setDefaultConnection($database_name);
            DB::purge($database_name);
            //DB::reconnect($database_name);

            $client_toke = Client::where('is_deleted', 0)->where('code', $shortCode)
            ->whereHas('getPreference',function($q)use($personal_access_token_v1){
                $q->where('personal_access_token_v1',$personal_access_token_v1);
            })->first(['id', 'name', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked']);
            if($client_toke)
            return $next($request);
        }
        abort(404);
    }
}


