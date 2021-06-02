<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
use App\Model\Client;
use App\Model\Cms;
use App\Model\SubClient;
use App\Model\TaskProof;
use App\Model\TaskType;
use App\Model\SmtpDetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Session;
use Illuminate\Support\Facades\Storage;
use Crypt;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
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
            'database_name' => ['required','unique:clients,database_name'],
            'custom_domain' => ['nullable','unique:clients,custom_domain'],
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
        DB::beginTransaction();
        try {
        $validator = $this->validator($request->all())->validate();
        
        $getFileName = NULL;

        // Handle File Upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $s3filePath = '/assets/Clientlogo/' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file,'public');
            $getFileName = $path;
        }

        $database_name = preg_replace('/\s+/', '', $request->database_name);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_password' => Crypt::encryptString($request->password),
            'phone_number' => $request->phone_number,
            'database_name' => $database_name,
            'company_name' => $request->company_name,
            'company_address'  => $request->company_address,
            'database_username' => env('DB_USERNAME'),
            'database_password' => env('DB_PASSWORD'),
            'logo' => isset($getFileName) ? $getFileName : 'assets/Clientlogo/5ff41c4b5a9f0.png/KQb50SOKZckXbcmMBXgqz3pqfCZcOTpkpljs8sJq.png',
            'status'=> 1,
            'timezone' => $request->timezone ? $request->timezone : 'America/New_York',
            'custom_domain'=> $request->custom_domain??'',
            'sub_domain'   => $request->sub_domain
        ];
        $data['code'] = $this->randomString();

        $client = Client::create($data);

        # if submit custom domain from god panel 
        if ($request->custom_domain && $request->custom_domain != $client->custom_domain) {
             $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $request->custom_domain);
             $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $request->custom_domain);
             $process = new Process(['/var/app/Automation/script.sh', $domain]);
             $process->run();
              
              #  executes after the command finishes
             if (!$process->isSuccessful()) {
                 return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => new ProcessFailedException($process)]));
             }
             $exists = Client::where('id','!=', $client->id)->where('custom_domain', $request->custom_domain)->count();
              if ($exists) {
                   return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
              }
              else{
                  Client::where('id', $client->id)->update(['custom_domain' => $request->custom_domain]);
             }
              
              
          }

         // $redis = Redis::connection();

         // $redis->set($database_name, json_encode($data));
         //$minutes = 600;
        Cache::set($database_name, $data);

        $this->dispatchNow(new ProcessClientDataBase($client->id));
        DB::commit();
       
        return redirect()->route('client.index')->with('success', 'Client Added successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('client.index')->with('error', $e->getMessage());
        }
        
    }

    private function randomString(){
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while(Client::where('code', $random_string )->exists()){
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;

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
            'database_name' => ['required'],
            'database_password' => ['required'],
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
        
        $getClient = Client::find($id);
        $getFileName = $getClient->logo;
        $removeDataFromRedis = Cache::forget($getClient->database_name);

        // Handle File Upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $s3filePath = '/assets/Clientlogo/' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file,'public');
            $getFileName = $path;
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
            'country_id' => $request->country ? $request->country : NULL,
            'timezone' => $request->timezone ? $request->timezone : 'America/New_York',
            'logo' => $getFileName,
            'sub_domain'   => $request->sub_domain
        ];
        
        $client = Client::where('id', $id)->update($data);
        $saveDataOnRedis = Cache::set($data['database_name'],$data);
        return redirect()->route('client.index')->with('success', 'Client Updated successfully!');
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
    public function storePreference(Request $request,$domain = '', $id)
    {
       
        $client = Client::where('code', $id)->firstOrFail(); 
        //update the client custom_domain if value is set //
        if ($request->domain_name == 'custom_domain') {
            // check the availability of the domain //
            $exists = Client::where('code', '<>', $id)->where('custom_domain', $request->custom_domain_name)->count();
            if ($exists) {
                return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['domain_name' => 'Domain name "' . $request->custom_domain_name . '" is not available. Please select a different domain']));
            }
            Client::where('id', $id)->update(['custom_domain' => $request->custom_domain_name]);
        }

        

        # if submit custom domain by client 
        if ($request->custom_domain && $request->custom_domain != $client->custom_domain) {
           $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $request->custom_domain);
           $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $request->custom_domain);
          // $process = new Process(['/var/app/Automation/script.sh', $domain]);
          $my_url =   $request->custom_domain;
          $process = shell_exec("/var/app/Automation/script.sh '".$my_url."' ");
          
         //  $process = shell_exec('env -i /var/app/Automation/script.sh' , $domain);
         //  $process->run();
            dd($process);
             # executes after the command finishes
           if (!$process->isSuccessful()) {
               return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $process]));
           }
            
           $connectionToGod = $this->createConnectionToGodDb($id);
            $exists = Client::where('code','<>', $id)->where('custom_domain', $request->custom_domain)->count();
            if ($exists) {
                return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
            }
            else{
                Client::where('code', $id)->update(['custom_domain' => $request->custom_domain]);
               $custom_db_name = Client::where('code', $id)->first();
               $connectionToLocal = $this->createConnectionToClientDb($custom_db_name->database_name);
               $dbname = DB::connection()->getDatabaseName();
               if($dbname != env('DB_DATABASE'))
               Client::update(['custom_domain' => $request->custom_domain]);
            }
            
            
        }

         # if submit sub_domain domain by client 
         if ($request->sub_domain && ($request->sub_domain != $client->sub_domain)) {
            $connectionToGod = $this->createConnectionToGodDb($id);
            $exists = Client::where('code','<>', $id)->where('sub_domain', $request->sub_domain)->count();
            if ($exists) {
                 return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['sub_domain' => 'Sub Domain name "' . $request->sub_domain . '" is not available. Please select a different domain']));
            }
            else{
               Client::where('code', $id)->update(['sub_domain' => $request->sub_domain]);
               $custom_db_name = Client::where('code', $id)->first();
               $connectionToLocal = $this->createConnectionToClientDb($custom_db_name->database_name);
               $dbname = DB::connection()->getDatabaseName();
               if($dbname != env('DB_DATABASE'))
               Client::query()->update(['sub_domain' => $request->sub_domain]);
               $new_domain_link = "http://".$request->sub_domain."".env('SUBDOMAIN','.royodispatch.com');
               return redirect()->to($new_domain_link);
            }
            
            
        }


        
        $updatePreference = ClientPreference::updateOrCreate([
            'client_id' => $id
        ], $request->all());        

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Preference updated successfully!',
                'data' =>    $updatePreference
            ]);
        } else {
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }
    }

    // ************* create connection with god panel database ******************************* /////////////////
    public function createConnectionToGodDb($id){
        $already_db = DB::connection()->getDatabaseName();
        $god_db = env('DB_DATABASE');
         $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $god_db,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];
            Config::set("database.connections.$god_db", $default);
            DB::setDefaultConnection($god_db);
            DB::purge($god_db);

            
    }

    // ************* create connection with existing db ******************************* /////////////////
    public function createConnectionToClientDb($db_name){
        $database_name = 'db_'.$db_name;
        $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $database_name,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];
            Config::set("database.connections.$database_name", $default);
            DB::setDefaultConnection($database_name);
            DB::purge($database_name);
    }

    /**
     * Store/Update Client Preferences 
     */
    public function ShowPreference()
    {
        $preference  = ClientPreference::where('client_id', Auth::user()->code)->first();
        $currencies  = Currency::orderBy('iso_code')->get();
        $cms         = Cms::all('content');
        $task_proofs = TaskProof::where('type','!=',0)->get();
        $task_list   = TaskType::all();
        //print_r($task_list); die;
        $subClients  = SubClient::all();
        
        return view('customize')->with(['preference' => $preference, 'currencies' => $currencies,'cms'=>$cms,'task_proofs' => $task_proofs,'task_list' => $task_list]);
    }


    /**
     * Show Configuration page 
     */
    public function ShowConfiguration()
    {
        $preference  = ClientPreference::where('client_id',Auth::user()->code)->first();
        $client      = Auth::user();
        $subClients  = SubClient::all();
        $smtp        = SmtpDetail::where('id',1)->first();
        return view('configure')->with(['preference' => $preference, 'client' => $client,'subClients'=> $subClients,'smtp_details'=>$smtp]);
    }

    /**
     * Show Options page 
     */
    public function ShowOptions()
    {
        $preference = ClientPreference::where('client_id',Auth::user()->id)->first();
        return view('options')->with(['preference' => $preference]);
    }

    public function cmsSave(Request $request,$id)
    {
        Cms::where('id',$id)->update(['content'=>$request->content]);
        return response()->json(true);
    }

    public function taskProof(Request $request)
    {
        $requestAll = $request->all();
        for ($i=1; $i <= 3 ; $i++) { 

            $check = TaskProof::where('id',$i)->first();

            if(isset($check)){
                $update                     = TaskProof::find($i);
            }else{
                $update                     = new TaskProof;
            }
                
            $update->image              = isset($requestAll['image_'.$i])? 1 : 0 ;
            $update->image_requried     = isset($request['image_requried_'.$i])? 1 : 0 ;
            $update->signature          = isset($request['signature_'.$i])? 1 : 0 ;
            $update->signature_requried = isset($request['signature_requried_'.$i])? 1 : 0 ;
            $update->note               = isset($request['note_'.$i])? 1 : 0 ;
            $update->note_requried      = isset($request['note_requried_'.$i])? 1 : 0 ;
            $update->barcode            = isset($request['barcode_'.$i])? 1 : 0 ;
            $update->barcode_requried   = isset($request['barcode_requried_'.$i])? 1 : 0 ;
            $update->save();                
        }
        
        return redirect()->route('preference.show')->with('success', 'Preference updated successfully!');
        
    }

    public function saveSmtp(Request $request)
    {
        $check = SmtpDetail::where('id',1)->first();

        if(isset($check)){
            $update                     = SmtpDetail::find(1);
        }else{
            $update                     = new SmtpDetail;
        }
            
        $update->client_id          = Auth::user()->id;
        $update->driver             = 'smtp';
        $update->host               = $request->host;
        $update->port               = $request->port;
        $update->encryption         = $request->encryption;
        $update->username           = $request->username;
        $update->password           = $request->password;
        $update->from_address       = $request->from_address;

        $update->save();
        return redirect()->route('configure')->with('success', 'Configure updated successfully!');
    }
    

}
