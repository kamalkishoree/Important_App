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
    public function getCode(Request $request)
    {
        $user = Client::select('id', 'company_name', 'database_name')
                    ->where('is_deleted', 0)->where('is_blocked', 0)->get();

        if($user){
            return response()->json([
                'data' => $user,
            ]);
        }else{

        }
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
