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
    use AuthenticatesUsers;

    public function ClientLogin(Request $request)
    {

        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);

        if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->route('index');
        }

        return redirect()->back()->with('Error', 'Invalid Credentials');
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
