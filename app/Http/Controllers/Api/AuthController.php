<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Agent;
use App\Model\AllocationRule;
use App\Model\Client;
use App\Model\ClientPreference;
use App\Model\BlockedToken;
use App\Model\Otp;
use App\Model\{TaskProof,TagsForTeam,SubAdminTeamPermissions,SubAdminPermissions,TagsForAgent};
use Validation;
use DB;
use JWT\Token;
use Crypt;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client as TwilioClient;
use Faker\Generator as Faker;
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
        
        $request->validate([
            'phone_number' => 'required',
        ]);

        
        $agent = Agent::where('phone_number', $request->phone_number)->first();

        if (!$agent) {
            return response()->json([
                'message' => 'User not found'], 404);
        }
        Otp::where('phone', $request->phone_number)->delete();
        $otp = new Otp();
        $otp->phone = $data['phone_number'] = $agent->phone_number;
        $otp->opt = $data['otp'] = rand(111111, 999999);
      //  $otp->opt = $data['otp'] = 123456;
        $otp->valid_till = $data['valid_till'] = Date('Y-m-d H:i:s', strtotime("+10 minutes"));
        $otp->save();

        $client_prefrerence = ClientPreference::where('id', 1)->first();
            
        //twilio opt code

        $token             = $client_prefrerence->sms_provider_key_2;
        $twilio_sid        = $client_prefrerence->sms_provider_key_1;
           
        try {
            $twilio = new TwilioClient($twilio_sid, $token);

            $message = $twilio->messages
                   ->create(
                       $agent->phone_number,  //to number
                     [
                                "body" => "Your Dispatcher verification code is: ".$data['otp']."",
                                "from" => $client_prefrerence->sms_provider_number   //form_number
                     ]
                   );
        } catch (\Exception $e) {
        }
           


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
    public function login(UserLogin $request)
    {
        $otp = Otp::where('phone', $request->phone_number)->where('opt', $request->otp)->orderBy('id', 'DESC')->first();
        $date = Date('Y-m-d H:i:s');

        if (!$otp) {
            return response()->json(['message' => 'Please enter a valid opt'], 422);
        }
       

        if ($date > $otp->valid_till) {
            return response()->json(['message' => 'Your otp has been expired. Please try again.'], 422);
        }

        
        $data = $agent = Agent::with('team')->where('phone_number', $request->phone_number)->first();

        
        if (!$agent) {
            return response()->json([
                'message' => 'User not found'], 404);
        }

        $prefer = ClientPreference::select('theme', 'distance_unit', 'currency_id', 'language_id', 'agent_name', 'date_format', 'time_format', 'map_type', 'map_key_1')->first();
        $allcation = AllocationRule::first('request_expiry');
        $prefer['alert_dismiss_time'] = (int)$allcation->request_expiry;
        $taskProof = TaskProof::all();
        Auth::login($agent);
        

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
        $agent['task_proof']       = $taskProof;
        //$data['token_type'] = 'Bearer';
        $agent['access_token'] = $token;
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
        Agent::where('id', Auth::user()->id)->update(['device_token'=>null,'device_type'=>null]);
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }



    
     /******************    ---- update Create Vendor Order -----   ******************/
     public function updateCreateVendorOrder(Request $request){
        $tags = TagsForAgent::get();

        $update_create = $this->updateCreateManagerOrder($request);
        return response()->json([
            'tags' => $tags,
            'message' => 'success',
            'status' => 200
        ], 200);
        

    }


   
    public function updateCreateManagerOrder($request)
    {   
        DB::beginTransaction();
        try {
            if(isset($request->email))
            $already_exists = Client::where('email',$request->email)->first();

            if(empty($already_exists)){
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'confirm_password' => Crypt::encryptString($request->password),
                    'phone_number' => $request->phone_number,
                    'all_team_access'=> 0,
                    'status' => 1,
                    'is_superadmin' => 0,
                ];

                $superadmin_data = Client::select('country_id', 'timezone', 'custom_domain', 'is_deleted', 'is_blocked', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'code', 'sub_domain')
                ->where('is_superadmin', 1)
                ->first()->toArray();
                $clientcode = $superadmin_data['code'];
                $superadmin_data['code'] = "";

                $finaldata = array_merge($data, $superadmin_data);
                            
                $subdmin = Client::create($finaldata);
                
                //update client code
                $codedata = [
                    'code' => $subdmin->id.'_'.$clientcode
                ];
                
                $clientcodeupdate = Client::where('id', $subdmin->id)->update($codedata);
                $request->permissions = [1,3,8,9,11];
                if ($request->permissions) {
                    $userpermissions = $request->permissions;
                    $addpermission = [];
                    $removepermissions = SubAdminPermissions::where('sub_admin_id', $subdmin->id)->delete();
                    for ($i=0;$i<count($userpermissions);$i++) {
                        $addpermission[] =  array('sub_admin_id' => $subdmin->id,'permission_id' => $userpermissions[$i]);
                    }
                    SubAdminPermissions::insert($addpermission);
                }


                $team = $this->createTeamFromManager($request,$clientcode);
                $request->team_permissions = [$team->id];
                if ($request->team_permissions) {
                    $teampermissions = $request->team_permissions;
                    $addteampermission = [];
                    $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $subdmin->id)->delete();
                    for ($i=0;$i<count($teampermissions);$i++) {
                        $addteampermission[] =  array('sub_admin_id' => $subdmin->id,'team_id' => $teampermissions[$i]);
                    }
                    SubAdminTeamPermissions::insert($addteampermission);
                }
            }
            else{
                $url = 'http://sales.dispatcher.com/team';
            }
            $url = 'http://sales.dispatcher.com/team';
            DB::commit();
            return response()->json([
                        'url' => $url,
                        'message' => 'success'
                    ], 200);
        }
        catch(\Exception $e){
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
       
        }
    }


    public function createTeamFromManager($request,$clientcode)
    {
        $request->tags = [$request->team_tag];
        $newtag = explode(",", $request->tags);
        $tag_id = [];
        foreach ($newtag as $key => $value) {
            if (!empty($value)) {
                $check = TagsForTeam::firstOrCreate(['name' => $value]);
                array_push($tag_id, $check->id);
            }
        }
        $data = [
            'manager_id'          => $request->vendor_id,
            'name'          => $request->name." Team",
            'client_id'     => $clientcode,
            'location_accuracy' => $request->location_accuracy??1,
            'location_frequency' => $request->location_frequency??1
        ];

        $team = Team::create($data);
        $team->tags()->sync($tag_id);

        if ($team->wasRecentlyCreated) {
            return $team;
        }
    }

  

}
