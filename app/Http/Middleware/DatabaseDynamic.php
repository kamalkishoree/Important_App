<?php

namespace App\Http\Middleware;

use App\Model\Client;
use Closure;
use Config;
use Illuminate\Support\Facades\Auth;

class DatabaseDynamic
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

        if(Auth::check()){
            
            $client = Auth::user();
           if($client){
              $database_name = 'db_'.$client->database_name;
              $default = [
                  'driver' => env('DB_CONNECTION','mysql'),
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
              Config::set("client_id", $client->id);
              Config::set("client_connected",true);
              Config::set("client_data",$client);
              DB::setDefaultConnection($database_name);
              DB::purge($database_name);
          }
      }
        return $next($request);
    }
}
