<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Model\SubAdmin;
use App\Model\Client;
use App\Model\Permissions;
use App\Model\Team;
use App\Model\SubAdminPermissions;
use App\Model\SubAdminTeamPermissions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Crypt;
use Illuminate\Support\Facades\DB;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$subadmins = SubAdmin::orderBy('id', 'DESC')->paginate(10);
        $subadmins = Client::where('is_superadmin',0)->where('id','!=',Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('subadmin.index')->with(['subadmins' => $subadmins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permissions::all();
        $teams = Team::all();
        return view('subadmin/form')->with(['permissions'=>$permissions,'teams'=>$teams]);
    }

    /**
     * Validation method for clients data 
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'phone_number' => ['required'],
            'password' => ['required']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$domain = '')
    {
        $validator = $this->validator($request->all())->validate();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_password' => Crypt::encryptString($request->password),
            'phone_number' => $request->phone_number,            
            'all_team_access'=> $request->all_team_access,
            'status' => $request->status,
            'is_superadmin' => 0,            
        ];

        $superadmin_data = Client::select('country_id','timezone','custom_domain','is_deleted','is_blocked','database_path','database_name','database_username','database_password','logo','company_name','company_address','code','sub_domain')        
        ->where('is_superadmin',1)
        ->first()->toArray();
        $clientcode = $superadmin_data['code'];
        $superadmin_data['code'] = "";

        $finaldata = array_merge($data,$superadmin_data);

        //for removing client code unique index
        //$getsubadmins = Client::where('is_superadmin',0)->count();        
        // if($getsubadmins==0)
        // {
        //     $query = "ALTER TABLE clients DROP INDEX `clients_code_unique`";
        //     DB::statement($query);
        // }               
        
        $subdmin = Client::create($finaldata);        
        //$subdmin = SubAdmin::create($data);
        
        //update client code
        $codedata = [
            'code' => $subdmin->id.'_'.$clientcode            
        ];
        
        $clientcodeupdate = Client::where('id', $subdmin->id)->update($codedata);

        if($request->permissions)
        {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = SubAdminPermissions::where('sub_admin_id', $subdmin->id)->delete();
            for($i=0;$i<count($userpermissions);$i++) 
            {
                $addpermission[] =  array('sub_admin_id' => $subdmin->id,'permission_id' => $userpermissions[$i]);
            }
            SubAdminPermissions::insert($addpermission);
        }

        if($request->team_permissions)
        {
            $teampermissions = $request->team_permissions;
            $addteampermission = [];
            $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $subdmin->id)->delete();
            for($i=0;$i<count($teampermissions);$i++) 
            {
                $addteampermission[] =  array('sub_admin_id' => $subdmin->id,'team_id' => $teampermissions[$i]);
            }
            SubAdminTeamPermissions::insert($addteampermission);
        }

        return redirect()->route('subadmins.index')->with('success', 'Subadmin Added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain = '',$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '',$id)
    {        
        // $subadmin = SubAdmin::find($id);
        $subadmin = Client::find($id);
        $permissions = Permissions::all();
        $teams = Team::all();
        $user_permissions = SubAdminPermissions::where('sub_admin_id', $id)->get();
        $team_permissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->get();
        //$subadmin = SubAdmin::where('id', $id)->first();
        // print_r($permissions); die;
        
        return view('subadmin/form')->with(['subadmin'=> $subadmin,'permissions'=>$permissions,'user_permissions'=>$user_permissions,'teams'=>$teams,'team_permissions'=>$team_permissions]);
    }

    protected function updateValidator(array $data,$id)
    {
        //print_r($data); die;
        return Validator::make($data, [

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',\Illuminate\Validation\Rule::unique('clients')->ignore($id)],            
            'phone_number' => ['required'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '',$id)
    {
        $validator = $this->updateValidator($request->all(),$id)->validate();
        
        
        $getSubadmin = SubAdmin::find($id);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'all_team_access'=> $request->all_team_access,            
            'status' => $request->status,
            
        ];
        if($request->password!="")
        {
            $data['password'] = Hash::make($request->password);
            $data['confirm_password'] = Crypt::encryptString($request->password);
        }        
        
        // $client = SubAdmin::where('id', $id)->update($data);
        $client = Client::where('id', $id)->update($data);

         //for updating permissions
        if($request->permissions)
        {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = SubAdminPermissions::where('sub_admin_id', $id)->delete();
            for($i=0;$i<count($userpermissions);$i++) 
            {
                $addpermission[] =  array('sub_admin_id' => $id,'permission_id' => $userpermissions[$i]);
            }
            SubAdminPermissions::insert($addpermission);
        }

        //for updating team permissions
        if($request->team_permissions)
        {
            $teampermissions = $request->team_permissions;
            $addteampermission = [];
            $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->delete();
            for($i=0;$i<count($teampermissions);$i++) 
            {
                $addteampermission[] =  array('sub_admin_id' => $id,'team_id' => $teampermissions[$i]);
            }
            SubAdminTeamPermissions::insert($addteampermission);
        }
        
        return redirect()->route('subadmins.index')->with('success', 'Subadmin Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '',$id)
    {
        // //$getSubadmin = SubAdmin::where('id', $id)->delete();
        // $getSubadmin = Client::where('id', $id)->delete();
        // $removepermissions = SubAdminPermissions::where('sub_admin_id', $id)->delete();
        // $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->delete();
        // return redirect()->back()->with('success', 'Subadmin deleted successfully!');
    }
}