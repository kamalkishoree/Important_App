<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Model\Client;
use App\Model\SubAdmin;
use Illuminate\Support\Facades\DB;
use Config;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function ClientLogin(Request $request)
    {

        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);

        //$subdomain = explode('.',$_SERVER['HTTP_HOST'])[0];        
        //$subdomain = "appi";
        //$check_subdomain = Client::where('sub_domain', $subdomain)->first();
        //$clients = SubAdmin::all();
        //print_r($clients); die;
        if($check_subdomain)    //subdomain found
        {
            //print_r($check_subdomain);
            $schemaName = 'db_'.$check_subdomain->database_name;
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

            // print_r($default); die;

            config(["database.connections.mysql.database" => null]);
            Config::set("database.connections.$schemaName", $default);
            
            config(["database.connections.mysql.database" => $schemaName]);
            DB::connection($schemaName);   
            
            // Config::set("database.connections.$schemaName", $default);
            // Config::set("client_id", 1);
            // Config::set("client_connected", true);
            // Config::set("client_data", $check_subdomain);
            // DB::setDefaultConnection($schemaName);
            // DB::purge($schemaName);
          
            //print_r(Auth::guard('subadmin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))); die;
            if (Auth::guard('subadmin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                               
                return redirect()->route('index');
            }

            if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            
                return redirect()->route('index');
            }


            return redirect()->back()->with('Error', 'Invalid Credentials');
        }else{  //subdomain not found
            if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            
                return redirect()->route('index');
            }
    
            return redirect()->back()->with('Error', 'Invalid Credentials');
        }
        

       

        // if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            
        //     return redirect()->route('index');
        // }

        // return redirect()->back()->with('Error', 'Invalid Credentials');
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function wrongurl()
    {
        return redirect()->route('wrong.client');;
    }
}
