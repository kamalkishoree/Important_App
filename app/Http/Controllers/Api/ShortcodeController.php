<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{User, Client};
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
        $client = Client::select('id', 'name', 'database_name',  'country', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked')
                    ->where('is_deleted', 0)->where('code', $request->shortCode)->first();

        
        if (!$client) {
            return response()->json([
                'error' => 'Company not found',
                'message' => 'Invalid short code. Please enter a valid short code.'], 404);
        }
        //print_r($client);die;

        if ($client->is_blocked == 1) {
            return response()->json([
                'error' => 'Blocked Company',
                'message' => 'Company has been blocked. Please contact administration.'], 404);
        }

        $img = env('APP_URL').'/assets/images/default_image.png';

        if (file_exists( public_path().'/assets/images/'.$client->logo)) {

            $img = public_path().'/assets/images/'.$client->logo;
        }
        $client->logo = $img;

        return response()->json([
            'data' => $client,
        ]);
    }

    /*public function getCode(Request $request)
    {
        $user = Client::select('id', 'company_name', 'database_name')
                    ->where('is_deleted', 0)->where('is_blocked', 0)->get();

        if($user){
            return response()->json([
                'data' => $user,
            ]);
        }else{
            
        }
    }*/
  
}
