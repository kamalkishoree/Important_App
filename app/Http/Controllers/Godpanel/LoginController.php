<?php

namespace App\Http\Controllers\Godpanel;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function Login(Request $request)
    {
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);
    
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $details = Auth::guard()->user();
            $user = $details['original'];
            return redirect()->route('god.dashboard');
    
        } else {
            return redirect()->back()->with('Error', 'Invalid Credentials');
        }
    }

    public function Logout()
    {
        Auth::logout();
        Auth::guard('client')->logout();
        return redirect()->route('get.god.login');
    }


    public function verifyCustomDomain(Request $request)
    {
        $process = new Process(['/var/app/Automation/script.sh', 'devtest1.focushires.com']);
        $process->run();
        
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        echo $process->getOutput();
    }
    
}



