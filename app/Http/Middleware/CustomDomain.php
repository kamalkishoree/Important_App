<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Model\{Client, ClientPreference};
use Config;
use Cache;
use Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client as TwilioC;
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
      $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $domain);
      $subDomain = explode('.', $domain);
      //dd($domain); yo.com ,
      
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
     $dbname = DB::connection()->getDatabaseName(); 
      if($domain != env('Main_Domain')){
            if($client && $dbname != 'db_'.$client->database_name){
            $database_name = 'db_'.$client->database_name;
            $database_host = !empty($client->database_host) ? $client->database_host : '127.0.0.1';
            $database_port = !empty($client->database_port) ? $client->database_port : '3306';
            $default = [
                'driver' => env('DB_CONNECTION','mysql'),
                'host' => $database_host,
                'port' => $database_port,
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
            Config::set("client_id",1);
            Config::set("client_connected",true);
            Config::set("client_data",$client);
            DB::setDefaultConnection($database_name);
            DB::purge($database_name);
            $dbname = DB::connection()->getDatabaseName(); 
            
          }
        

      }else{
        return view('pages/404');
      }
      
      return $next($request);
    }
}