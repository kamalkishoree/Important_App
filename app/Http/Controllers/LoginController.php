<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
 use App\Model\Client;
// use App\Model\SubAdmin;
// use Illuminate\Support\Facades\DB;
// use Config;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;
use DB;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function ClientLogin(Request $request)
    {
       // print_r(Client::all()->toArray()); die;
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);        
                

        if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {        
            //dd(Auth::user());
            return redirect()->route('index');
        }

        return redirect()->back()->with('Error', 'Invalid Credentials');    
        

       

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
