<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Model\Client;
use App\Model\Permissions;
use App\Model\Team;
use App\Model\SubAdminPermissions;
use App\Model\SubAdminTeamPermissions;
use App\Model\Warehouse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Crypt;
use Illuminate\Support\Facades\DB;
use App\Model\Countries;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(REQUEST $request)
    {
        $manager_type = $request->manager_type;
        $warehouseId = $request->warehouse;
        $warehouse_mode = checkWarehouseMode();
        if($warehouse_mode['show_warehouse_module'] == 1){
            $subadmins = Client::with('warehouse')->where('is_superadmin', 0)->where('id', '!=', Auth::user()->id);
        }else{
            $subadmins = Client::where('is_superadmin', 0)->where('id', '!=', Auth::user()->id)->where('manager_type', 0);
        }
        if(checkColumnExists('clients', 'manager_type')){
            if($manager_type != "all" && $manager_type != null){
                $subadmins = $subadmins->where('manager_type', $manager_type);
            }
            if($warehouseId != '' && $warehouseId != null){
                $subadmins = $subadmins->whereHas('warehouse', function($q) use ($warehouseId){
                    $q->where('warehouses.id', '=', $warehouseId);
                });
            }
        }
        $warehouses = [];
        if(checkTableExists('warehouses')){
            $warehouses = Warehouse::all();
        }
        $subadmins = $subadmins->orderBy('id', 'DESC')->paginate(10);
        return view('subadmin.index')-> with(['subadmins' => $subadmins, 'warehouses' => $warehouses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $getAdminCurrentCountry = Countries::where('id', '=', Auth::user()->country_id)->get()->first();
        if(!empty($getAdminCurrentCountry)){
            $countryCode = $getAdminCurrentCountry->code;
        }else{
            $countryCode = '';
        }
        $permissions = Permissions::all();
        $teams = Team::all();
        $warehouses = [];
        if(checkTableExists('warehouses')){
            $warehouses = Warehouse::all();
        }
        return view('subadmin/form')->with(['permissions'=>$permissions,'teams'=>$teams, 'selectedCountryCode' => $countryCode, 'warehouses' => $warehouses]);
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
    public function store(Request $request, $domain = '')
    {
        $validator = $this->validator($request->all())->validate();
        $all_team_access = ($request->manager_type == 0) ? $request->all_team_access : '1';
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_password' => Crypt::encryptString($request->password),
            'phone_number' => $request->phone_number,
            'dial_code' => $request->dialCode??null,
            'all_team_access'=> $all_team_access,
            'status' => $request->status,
            'is_superadmin' => 0
        ];
        if(checkColumnExists('clients', 'manager_type')){
            $manager_type = $request->manager_type ?? 0;
            $data['manager_type'] = $manager_type;
        }

        $superadmin_data = Client::select('country_id', 'timezone', 'custom_domain', 'is_deleted', 'is_blocked', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'code', 'sub_domain')
        ->where('is_superadmin', 1)
        ->first()->toArray();
        $clientcode = $superadmin_data['code'];
        $superadmin_data['code'] = "";

        $finaldata = array_merge($data, $superadmin_data);
                     
        $subdmin = Client::create($finaldata);
        
        if($request->manager_type == 1){
            if(checkTableExists('warehouse_manager_relation')){
                $warehouses = $request->input('warehouses');
                $subdmin->warehouse()->sync($warehouses);
            }
        }
        
        //update client code
        $codedata = [
            'code' => $subdmin->id.'_'.$clientcode
        ];
        
        $clientcodeupdate = Client::where('id', $subdmin->id)->update($codedata);

        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = SubAdminPermissions::where('sub_admin_id', $subdmin->id)->delete();
            for ($i=0;$i<count($userpermissions);$i++) {
                $addpermission[] =  array('sub_admin_id' => $subdmin->id,'permission_id' => $userpermissions[$i]);
            }
            SubAdminPermissions::insert($addpermission);
        }

        if ($request->team_permissions) {
            $teampermissions = $request->team_permissions;
            $addteampermission = [];
            $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $subdmin->id)->delete();
            for ($i=0;$i<count($teampermissions);$i++) {
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
    public function show($domain = '', $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $subadmin = Client::find($id);
        if(checkTableExists('warehouses')){
            $subadmin = Client::with(['warehouse'])->find($id);
        }
        // dd($subadmin);
        $permissions = Permissions::all();
        $teams = Team::all();
        $user_permissions = SubAdminPermissions::where('sub_admin_id', $id)->get();
        $team_permissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->get();

        $getAdminCurrentCountry = Countries::where('id', '=', Auth::user()->country_id)->get()->first();
        if(!empty($getAdminCurrentCountry)){
            $countryCode = $getAdminCurrentCountry->code;
        }else{
            $countryCode = '';
        }
        $warehouses = [];
        if(checkTableExists('warehouses')){        
            $warehouses = Warehouse::all();
        }
        return view('subadmin/form')->with(['subadmin'=> $subadmin,'permissions'=>$permissions, 'selectedCountryCode' => $countryCode, 'user_permissions'=>$user_permissions,'teams'=>$teams,'team_permissions'=>$team_permissions, 'warehouses'=>$warehouses]);
    }

    protected function updateValidator(array $data, $id)
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
    public function update(Request $request, $domain = '', $id)
    {
        $validator = $this->updateValidator($request->all(), $id)->validate();
        $all_team_access = ($request->manager_type == 0) ? $request->all_team_access : '1';
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'all_team_access'=> $all_team_access,
            'dial_code' => $request->dialCode??null,
            'status' => $request->status
        ];
        if(checkColumnExists('clients', 'manager_type')){
            $data['manager_type'] = $request->manager_type;
        }
        if ($request->password!="") {
            $data['password'] = Hash::make($request->password);
            $data['confirm_password'] = Crypt::encryptString($request->password);
        }
                
        $client = Client::find($id);
        $client->update($data);
        if($request->manager_type == 1){
            if(checkTableExists('warehouse_manager_relation')){
                $warehouses = $request->input('warehouses');
                $client->warehouse()->sync($warehouses);
            }
        }else{
            if(checkTableExists('warehouse_manager_relation')){
                $warehouses = [];
                $client->warehouse()->sync($warehouses);
            }
        }
        //for updating permissions
        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = SubAdminPermissions::where('sub_admin_id', $id)->delete();
            for ($i=0;$i<count($userpermissions);$i++) {
                $addpermission[] =  array('sub_admin_id' => $id,'permission_id' => $userpermissions[$i]);
            }
            SubAdminPermissions::insert($addpermission);
        }

        //for updating team permissions
        if ($request->team_permissions) {
            $teampermissions = $request->team_permissions;
            $addteampermission = [];
            $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->delete();
            for ($i=0;$i<count($teampermissions);$i++) {
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
    public function destroy($domain = '', $id)
    {
        // $getSubadmin = Client::where('id', $id)->delete();
        // $removepermissions = SubAdminPermissions::where('sub_admin_id', $id)->delete();
        // $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->delete();
        // return redirect()->back()->with('success', 'Subadmin deleted successfully!');
    }
}
