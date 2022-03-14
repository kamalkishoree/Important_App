<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Model\{Client, PaymentOption};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validation;
use DB;

class ShortcodeController extends BaseController
{
    /**
     * Get Company ShortCode
     *

     */
    public function validateCompany(Request $request)
    {
        $client = Client::with('getPreference')->where('is_deleted', 0)->where('code', $request->shortCode)->select('id','country_id', 'name', 'phone_number', 'email', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked')->with('getCountrySet')->first();

        
        if (!$client) {
            return response()->json([
                'error' => 'Company not found',
                'message' => 'Invalid short code. Please enter a valid short code.'], 404);
        }
        if ($client->is_blocked == 1) {
            return response()->json([
                'error' => 'Blocked Company',
                'message' => 'Company has been blocked. Please contact administration.'], 404);
        }
        
        $img = env('APP_URL').'/assets/images/default_image.png';

        if (file_exists(public_path().'/assets/images/'.$client->logo)) {
            $img = public_path().'/assets/images/'.$client->logo;
        }
        $client->logo = \Storage::disk("s3")->url($client->logo);
        
        $payment_codes = ['stripe'];
        $payment_creds = PaymentOption::select('code', 'credentials')->whereIn('code', $payment_codes)->where('status', 1)->get();
        if ($payment_creds) {
            foreach ($payment_creds as $creds) {
                $creds_arr = json_decode($creds->credentials);
                if ($creds->code == 'stripe') {
                    $client->getPreference->stripe_publishable_key = (isset($creds_arr->publishable_key) && (!empty($creds_arr->publishable_key))) ? $creds_arr->publishable_key : '';
                }
            }
        }

        return response()->json([
            'data' => $client,
            'status' => 200,
            'message' => __('success')
        ]);
    }
}
