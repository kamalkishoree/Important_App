<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{User, Agent, Client, ClientPreference, BlockedToken};
use Validation;
use DB;
use JWT\Token;

class AuthController extends BaseController
{

	/**
     * Login user and create token
     *
     * @param  [string] phone_number
     * @param  [string] OTP
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function sendOtp(Request $request)
    {
    	//echo "Connected ".DB::connection()->getDatabaseName();
        $request->validate([
            'phone_number' => 'required|numeric',
        ]);
        
        $agent = Agent::where('phone_number', $request->phone_number)->first();

        if (!$agent) {
	        return response()->json([
	            'error' => 'User not found'], 404);
	    }

        $data['phone_number'] = $agent->phone_number;
        $data['otp'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);

        //parent::sendSms($request->phone_number, 'Your OTP for login into Royo App is ' . $data['otp']);

        return response()->json([
            'data' => $data,
        ]);

    }

    /**
     * Login user and create token
     *
     * @param  [string] phone_number
     * @param  [string] OTP
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric',
            'device_type' => 'required|string',
            'device_token' => 'required|string',
            'otp' => 'required|string|min:6|max:6',

        ]);
        
        $data['agent'] = $agent = Agent::with('team', 'geoFence.geo')->where('phone_number', $request->phone_number)->first();

        
        if (!$agent) {
	        return response()->json([
	            'error' => 'User not found'], 404);
	    }

	    $prefer = ClientPreference::select('theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'date_format', 'time_format', 'map_type')->first();
        
        Auth::login($agent);
        /*$tokenResult = $agent->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();*/

        $token = Token::make([
            'key' => 'secret',
            'issuer' => 'artisangang',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256'
        ])->get();


        try {
            Token::validate($token, 'secret');
        } catch (\Exception $e) {
        }

        $agent->device_type = $request->device_type;
        $agent->device_token = $request->device_token;
        $agent->access_token = $token;
        $agent->save();

        $data['client_preference'] = $prefer;
        $data['token_type'] = 'Bearer';
        $data['access_token'] = $token;
        //$data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

        return response()->json([
        	'data' => $data,
        ]);

    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
    	$blockToken = new BlockedToken();
        $header = $request->header();
        $blockToken->token = $header['authorization'][0];
        $blockToken->expired = '1';
        $blockToken->save();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
}
