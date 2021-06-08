<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;

class BaseController extends Controller
{

	protected function sendSms($recipients, $message)
	{
	    $sid = getenv("TWILIO_SID");
	    $token = getenv("TWILIO_AUTH_TOKEN");
	    $twilio_number = getenv("TWILIO_NUMBER");
	    $client = new Client($account_sid, $auth_token);
	    $client->messages->create('+91'.$recipients, 
	            ['from' => $twilio_number, 'body' => $message] );
	}
 
}