<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
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

	public function paginate($items, $perPage = 15, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage), 
		$items->count(), $perPage, $page, $options);
	}
 
}