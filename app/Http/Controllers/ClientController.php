<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Client;
use App\Model\ClientPreference;
use App\Model\Currency;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\ProcessClientDatabase;
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //     $client = Client::first();
    //     Config::set("database.connections.mysql2", [
    //         "driver" => "mysql",
    //         "port" => '3306',
    //         "host" => $client->database_path,
    //         "database" => $client->database_name,
    //         "username" => $client->database_username,
    //         "password" => $client->database_password
    //     ]);
    //     DB::purge('mysql2');
    //    $user =  DB::connection('mysql2')->table('users')->select('email')->first();
            
        $clients = Client::where('is_deleted', 0)->orderBy('created_at', 'DESC')->paginate(10);
        return view('godpanel/client')->with(['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('godpanel/update-client');
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
            'password' => ['required'],
            'database_path' => ['required'],
            'database_name' => ['required'],
            'database_username' => ['required'],
            'database_password' => ['required'],
            'company_name' => ['required'],
            'company_address' => ['required'],
            'custom_domain' => ['required'],
            //'logo' => ['required'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
    //     $slug = Str::slug($request->name);

    //    // $abc = str_slug();
    //     dd($slug);
        $validator = $this->validator($request->all())->validate();
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator, 'add');
        // }
       
        $getFileName = NULL;
        
        // Handle File Upload
        if($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); 
            $fileNameToStore = $filename.'_'.time().'.'.$file->getClientOriginalExtension();  
            $file->move(public_path().'/clients',$fileNameToStore);
            $getFileName = $fileNameToStore;
        }
           
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'phone_number' => $request->phone_number,
            'database_path' => $request->database_path,
            'database_name' => preg_replace('/\s+/', '', $request->database_name),
            'database_username' => $request->database_username,
            'database_password' => $request->database_password,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'custom_domain' => $request->custom_domain,
            'logo' => $getFileName,
        ];

        $client = Client::create($data);
        $this->dispatchNow(new ProcessClientDataBase($client->id));
        return redirect()->route('client.index')->with('success', 'Client Added successfully!');
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        return redirect()->back()->with(['getClient' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        return view('godpanel/update-client')->with('client', $client);
    }

    /**
     * Validation method for clients Update 
    */
    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'database_path' => ['required'],
            'database_name' => ['required'],
            'database_username' => ['required'],
            'database_password' => ['required'],
            'company_name' => ['required'],
            'company_address' => ['required'],
            'custom_domain' => ['required'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->updateValidator($request->all())->validate();
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator, 'update');
        // }
        $getClient = Client::find($id);
        $getFileName = $getClient->logo;
        
        // Handle File Upload
        if($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); 
            $fileNameToStore = $filename.'_'.time().'.'.$file->getClientOriginalExtension();  
            $file->move(public_path().'/clients',$fileNameToStore);
            $getFileName = $fileNameToStore;
        }
           
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $getClient->password,
            'phone_number' => $request->phone_number,
            'database_path' => $request->database_path,
            'database_name' => $request->database_name,
            'database_username' => $request->database_username,
            'database_password' => $request->database_password,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'custom_domain' => $request->custom_domain,
            'country' => $request->country ? $request->country : NULL,
            'timezone' => $request->timezone ? $request->timezone : NULL,
            'logo' => $getFileName,
        ];

        $client = Client::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Client Updated successfully!');
        //return redirect()->route('client.index')->with('success', 'Client Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $getClient = Client::where('id', $id)->update(['is_deleted' => 1]);
        return redirect()->back()->with('success', 'Client deleted successfully!');
    }

    /**
     * Store/Update Client Preferences 
    */
    public function storePreference(Request $request, $id){

        
        //update the client custom_domain if value is set //
        if($request->domain_name == 'custom_domain'){
            // check the availability of the domain //
            $exists = Client::where('id','<>',$id)->where('custom_domain',$request->custom_domain_name)->count();
            if($exists){
                return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['domain_name'=>'Domain name "'.$request->custom_domain_name.'" is not available. Please select a different domain']));
            }
            Client::where('id',$id)->update(['custom_domain'=>$request->custom_domain_name]);
        }

        $updatePreference = ClientPreference::updateOrCreate([
            'client_id' => $id
        ],$request->all());

        if($request->ajax()){
            return response()->json([
                'status'=>'success',
                'message' => 'Preference updated successfully!',
                'data' => $updatePreference
            ]);
        }
        else{
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }
    }

    /**
     * Store/Update Client Preferences 
    */
    public function ShowPreference(){
        $preference = Auth::user()->getPreference;
        $currencies = Currency::orderBy('iso_code')->get();
        return view('customize')->with(['preference' => $preference,'currencies'=>$currencies]);
    }


    /**
     * Show Configuration page 
    */
    public function ShowConfiguration(){
        $preference = Auth::user()->getPreference;
        $client = Auth::user();
        return view('configure')->with(['preference' => $preference,'client'=>$client]);
    }

     /**
     * Show Options page 
    */
    public function ShowOptions(){
        $preference = Auth::user()->getPreference;
        return view('options')->with(['preference' => $preference]);
    }
}