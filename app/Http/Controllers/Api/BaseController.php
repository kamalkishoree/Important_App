<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Twilio\Rest\Client as TwilioClient;

class BaseController extends Controller
{
    use \App\Traits\smsManager;

	protected function sendSms($recipients, $message)
	{
	    $sid = getenv("TWILIO_SID");
	    $token = getenv("TWILIO_AUTH_TOKEN");
	    $twilio_number = getenv("TWILIO_NUMBER");
	    $client = new Client($account_sid, $auth_token);
	    $client->messages->create('+91'.$recipients,
	            ['from' => $twilio_number, 'body' => $message]
            );
	}

	public function paginate($items, $perPage = 15, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage),
		$items->count(), $perPage, $page, $options);
	}

    protected function sendSms2($to, $body){
        try{
            $client_preference =  getClientPreferenceDetail();
            if($client_preference->sms_provider == 1)
            {
                $sms_key = $client_preference->sms_provider_key_1;
                $sms_secret = $client_preference->sms_provider_key_2;
                $sms_from  = $client_preference->sms_provider_number;

                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }elseif($client_preference->sms_provider == 2) //for mtalkz gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->mTalkz_sms($to,$body,$crendentials);
            }elseif($client_preference->sms_provider == 3) //for mazinhost gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->mazinhost_sms($to,$body,$crendentials);
            }else{
                $sms_key = $client_preference->sms_provider_key_1;
                $sms_secret = $client_preference->sms_provider_key_2;
                $sms_from  = $client_preference->sms_provider_number;
                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }
        }
        catch(\Exception $e){
            return '2';
        }
        return '1';
	}

}
