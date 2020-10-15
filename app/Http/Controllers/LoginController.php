<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;

class LoginController extends Controller
{

    public function ClientLogin(Request $request)
    {

        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {



            return redirect()->route('index');
        }

        return back()->withInput($request->only('email', 'remember'));
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function cacheget($url)
    {
    
         return $value = Cache::get('anil');
    }
}
