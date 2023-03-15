<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConnectDbForDispatch
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
       
        $database_name = $database = 'royodelivery_db';
        $header = $request->header();
        
       
        if (array_key_exists("shortcode", $header)){
            $shortcode =  $header['shortcode'][0];
        }
        
        $client = Client::where('is_deleted', 0)->where('code', $shortcode)->first(['id', 'name', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked']);
       
        if (!$client) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid short code. Please enter a valid short code.']);
        }
        if ($client->is_blocked == 1) {
            return response()->json([
                'status' => 400,
                'message' => 'Company has been blocked. Please contact administration.']);
        }
        if (isset($client)) {
            $database_name =  'db_'.$client['database_name'];
            $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
            $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
            $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
            $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', 'admin@123');
            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => $database_host,
                'port' => $database_port,
                'database' => $database_name,
                'username' => $database_username,
                'password' => $database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];
            Config::set("database.connections.$database_name", $default);
            DB::setDefaultConnection($database_name);
            DB::purge($database_name);
            //DB::reconnect($database_name);
            return $next($request);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Order Panel API Values']);
        }
    }
}
