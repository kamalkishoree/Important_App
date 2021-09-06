<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Model\Client;
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
        $client = Client::where('is_deleted', 0)->where('code', $request->shortCode)->with('getCountry')->first(['id','country_id', 'name', 'database_name', 'timezone', 'custom_domain', 'logo', 'company_name', 'company_address', 'is_blocked']);

        
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

        return response()->json([
            'data' => $client,
        ]);
    }
}
