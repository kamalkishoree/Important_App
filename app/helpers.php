<?php
use Carbon\Carbon;
use App\Model\ClientPreference;
use App\Model\Client as ClientData;
use App\Model\PaymentOption;

function pr($var) {
  	echo '<pre>';
	print_r($var);
  	echo '</pre>';
    exit();
}
function http_check($url) {
    $return = $url;
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $return = 'http://' . $url;
    }
    return $return;
}

function getMonthNumber($month_name){
    if($month_name == 'January'){
        return 1;
    }else if($month_name == 'February'){
        return 2;
    }else if($month_name=='March'){
        return 3;
    }else if($month_name=='April'){
        return 4;
    }else if($month_name=='May'){
        return 5;
    }else if($month_name=='June'){
        return 6;
    }else if($month_name=='July'){
        return 7;
    }else if($month_name=='August'){
        return 8;
    }else if($month_name=='September'){
        return 9;
    }else if($month_name=='October'){
        return 10;
    }else if($month_name=='November'){
        return 11;
    }else if($month_name=='December'){
        return 12;
    }
}
function generateOrderNo($length = 8){
    $number = '';
    do {
        for ($i=$length; $i--; $i>0) {
            $number .= mt_rand(0,9);
        }
    } while (!empty(\DB::table('orders')->where('order_number', $number)->first(['order_number'])) );
    return $number;
}
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s'){
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}
function getClientPreferenceDetail()
{
    $client_preference_detail = ClientPreference::first();
    // list($r, $g, $b) = sscanf($client_preference_detail->web_color, "#%02x%02x%02x");
    // $client_preference_detail->wb_color_rgb = "rgb(".$r.", ".$g.", ".$b.")";
    return $client_preference_detail;
}
function getClientDetail()
{
    $clientData = ClientData::first();
    $clientData->logo_image_url = $clientData ? $clientData->logo['image_fit'].'150/92'.$clientData->logo['image_path'] : " ";
    return $clientData;
}
function getRazorPayApiKey()
{
    $razorpay_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'razorpay')->where('status', 1)->first();
    $api_key_razorpay = "";
    if($razorpay_creds)
    {
        $creds_arr_razorpay = json_decode($razorpay_creds->credentials);
        $api_key_razorpay = (isset($creds_arr_razorpay->api_key)) ? $creds_arr_razorpay->api_key : '';
    }
    return $api_key_razorpay;
}

function dateTimeInUserTimeZone($date, $timezone, $showDate=true, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $date_format = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    if($date_format == 'DD/MM/YYYY'){
        $date_format = 'DD-MM-YYYY';
    }
    $time_format = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    $dateFormat = '';
    if($showDate){
        $dateFormat = $date_format;
    }
    if($showTime){
        if($showSeconds){
            $secondsKey = ':ss';
        }
        if($time_format == '12'){
            $timeFormat = ' hh:mm'.$secondsKey.' A';
        }else{
            $timeFormat = ' HH:mm'.$secondsKey;
        }
    }

    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
}

function helper_number_formet($number){
    return number_format($number,2);
}
