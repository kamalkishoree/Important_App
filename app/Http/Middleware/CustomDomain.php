<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Model\{Client, ClientPreference};
use Config;
use Cache;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CustomDomain
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
   
      $domain = $request->getHost();
      // $domain    = str_replace(array('http://', '.dispatcher.test/login'), '', $domain);
      $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $domain);
      $subDomain = explode('.', $domain);
      //dd($domain); 
      
      //$existRedis = Redis::get($domain);
        $existRedis = '';
      //$callback = http://local.myorder.com/auth/facebook/callback

      if(!$existRedis){
        
        $client = Client::select('*')
                    ->where(function($q) use($domain, $subDomain){
                              $q->where('custom_domain', $domain)
                                ->orWhere('sub_domain', $subDomain[0]);
                                //->orWhere('database_name', $subDomain[0]);
                    })
                    ->firstOrFail();
        
         //Redis::set($domain, json_encode($client->toArray()), 'EX', 36000);

         //$existRedis = Redis::get($domain);

         $saveDataOnRedis = Cache::set('clientdetails',$client);
         
      }
      $callback = '';

      $redisData = $client;
     // echo '<pre>';print_r($redisData);

      if($domain){
          // $database_name = 'db_'.$redisData->database_name;
          // $database_host = !empty($redisData->database_host) ? $redisData->database_host : '127.0.0.1';
          // $database_port = !empty($redisData->database_port) ? $redisData->database_port : '3306';
          // $default = [
          //     'driver' => env('DB_CONNECTION','mysql'),
          //     'host' => $database_host,
          //     'port' => $database_port,
          //     'database' => $database_name,
          //     'username' => $redisData->database_username ?? env('DB_USERNAME'),
          //     'password' => $redisData->database_password ?? env('DB_PASSWORD'),
          //     'charset' => 'utf8mb4',
          //     'collation' => 'utf8mb4_unicode_ci',
          //     'prefix' => '',
          //     'prefix_indexes' => true,
          //     'strict' => false,
          //     'engine' => null
          // ];
          // Config::set("database.connections.$database_name", $default);
          // Config::set("client_id", 1);
          // Config::set("client_connected", true);
          // Config::set("client_data", $redisData);
          // DB::setDefaultConnection($database_name);
          // DB::purge($database_name);

      }else{
        return view('pages/404');
      }
      
      return $next($request);
    }
}