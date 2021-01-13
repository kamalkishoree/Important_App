<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{User, Agent, Client, ClientPreference, BlockedToken, Otp};
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
	            'message' => 'User not found'], 404);
	    }
        $otp = new Otp();
        $otp->phone = $data['phone_number'] = $agent->phone_number;
        $otp->opt = $data['otp'] = rand(111111,999999);
        $otp->valid_till = $data['valid_till'] = Date('Y-m-d H:i:s', strtotime("+10 minutes"));

        $otp->save();

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

        $otp = Otp::where('phone', $request->phone_number)->where('opt', $request->otp)->first();

        if(!$otp){
            return response()->json(['message' => 'Please enter a valid opt'], 422);
        }

        $date = Date('Y-m-d H:i:s');

        if(!$otp){
            return response()->json(['message' => 'Please enter a valid opt'], 422);
        }
        if($date > $otp->valid_till){
            return response()->json(['message' => 'Your otp has been expired. Please try again.'], 422);
        }

        
        $data = $agent = Agent::with('team')->where('phone_number', $request->phone_number)->first();

        
        if (!$agent) {
	        return response()->json([
	            'message' => 'User not found'], 404);
	    }

	    $prefer = ClientPreference::select('theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'date_format', 'time_format', 'map_type','map_key_1')->first();
        
        Auth::login($agent);
        /*$tokenResult = $agent->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();*/

        $token1 = new Token;

        $token = $token1->make([
            'key' => 'codebrewInd',
            'issuer' => 'codebrewInnovation',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('driver_id', $agent->id);

        try {
            Token::validate($token, 'secret');
        } catch (\Exception $e) {
        }

        $agent->device_type = $request->device_type;
        $agent->device_token = $request->device_token;
        $agent->access_token = $token;
        $agent->save();

        $agent['client_preference'] = $prefer;
        //$data['token_type'] = 'Bearer';
        $agent['access_token'] = $token;
        //$data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

        return response()->json([
        	'data' => $agent,
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
