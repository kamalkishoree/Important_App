<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;
use DB,Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function ClientLogin(Request $request)
    {
        try {
            $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);
        
            if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                $client = Client::with(['getAllocation', 'getPreference'])->where('email', $request->email)->first();
                if ($client->is_blocked == 1 || $client->is_deleted == 1) {
                    Auth::logout();
                    return redirect()->back()->with('Error', 'Your account has been blocked by admin. Please contact administration.');
                }
                if ($client->status == 3) {
                    Auth::logout();
                    return redirect()->back()->with('Error', 'Your account in In-Active. Please contact administration.');
                }
                return redirect()->route('index');
            }
       
            return redirect()->back()->with('Error', 'Invalid Credentials');
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function Logout()
    {
        Auth::logout();
        Auth::guard('client')->logout();
        return redirect()->route('login');
    }

    public function wrongurl()
    {
        return redirect()->route('wrong.client');
    }



    

    public function getOrderSession(Request $request){
        try {
          
        $client = Client::where('public_login_session',$request->set_unique_order_login)->first();
        $password = "969648tag-set".$client->id;
            if (Auth::guard('client')->attempt(['email' => $client->email, 'password' => $password], $request->get('remember'))) {
                $update_token = Client::where('id',$client->id)->update(['public_login_session' => '']);
                $client = Client::with(['getAllocation', 'getPreference'])->where('email', $request->email)->first();
                if ($client->is_blocked == 1 || $client->is_deleted == 1) {
                    Auth::logout();
                    return redirect()->back()->with('Error', 'Your account has been blocked by admin. Please contact administration.');
                }
                if ($client->status == 3) {
                    Auth::logout();
                    return redirect()->back()->with('Error', 'Your account in In-Active. Please contact administration.');
                }
                return redirect()->route('index');
            }
            
            echo 'Caught exception: Error';
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    
   
}
