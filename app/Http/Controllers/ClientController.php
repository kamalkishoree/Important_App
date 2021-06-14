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
   

    private function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while (Client::where('code', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

  

    /**
     * Store/Update Client Preferences
     */
    public function storePreference(Request $request, $domain = '', $id)
    {
        $client = Client::where('code', $id)->firstOrFail();
        # if submit custom domain by client
        if ($request->custom_domain && $request->custom_domain != $client->custom_domain) {
            try {
                $domain    = str_replace(array('http://', config('domainsetting.domain_set')), '', $request->custom_domain);
                $domain    = str_replace(array('https://', config('domainsetting.domain_set')), '', $request->custom_domain);
                $my_url =   $request->custom_domain;
                
                $postdata =  ['domain' => $my_url];
                $client = new \GuzzleHttp\Client(['headers' => ['client' => 'newclient1',
                'content-type' => ' multipart/form-data']
                    ]);

                $client = new \GuzzleHttp\Client(['headers' => ['content-type' => 'application/json']]);                                
                            
                $url = \env('ShellNodeApi','localhost:3000/add_subdomain');                      
                $res = $client->post($url,
                                ['form_params' => (
                                    $postdata
                                )]
                );
               // $process = shell_exec("/var/app/Automation/script.sh '".$my_url."' ");
            } catch (Exception $e) {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $e->getMessage()]));
            }
          
            
            $connectionToGod = $this->createConnectionToGodDb($id);
            $exists = Client::where('code', '<>', $id)->where('custom_domain', $request->custom_domain)->count();
            if ($exists) {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
            } else {
                Client::where('code', $id)->update(['custom_domain' => $request->custom_domain]);
                $custom_db_name = Client::where('code', $id)->first();
                $connectionToLocal = $this->createConnectionToClientDb($custom_db_name->database_name);
                $dbname = DB::connection()->getDatabaseName();
                if ($dbname != env('DB_DATABASE')) {
                    Client::where('id', '!=', 0)->update(['custom_domain' => $request->custom_domain]);
                }
            }
        }

        # if submit sub_domain domain by client
        if ($request->sub_domain && ($request->sub_domain != $client->sub_domain)) {
            $validator = Validator::make($request->all(), [
                    'sub_domain' => 'required|min:4',
                ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $update_sub_domain = $this->updateSubDomainFromClient($request, $id);
            if ($update_sub_domain == true) {
                $new_domain_link = "http://".$request->sub_domain."".env('SUBDOMAIN', '.royodispatch.com');
                return redirect()->to($new_domain_link);
            } else {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['sub_domain' => 'Sub Domain name "' . $request->sub_domain . '" is not available. Please select a different domain']));
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


    // ************* update Sub Domain From Client ******************************* /////////////////
    public function updateSubDomainFromClient($request, $id)
    {
        $connectionToGod = $this->createConnectionToGodDb($id);
        $exists = Client::where('code', '<>', $id)->where('sub_domain', $request->sub_domain)->count();
        if ($exists || $request->sub_domain == 'api' ||  $request->sub_domain == 'god'  ||  $request->sub_domain == 'godpanel'  ||  $request->sub_domain == 'admin') {
            return false;
        } else {
            Client::where('code', $id)->update(['sub_domain' => $request->sub_domain]);
            $custom_db_name = Client::where('code', $id)->first();
            $connectionToLocal = $this->createConnectionToClientDb($custom_db_name->database_name);
            $dbname = DB::connection()->getDatabaseName();
            if ($dbname != env('DB_DATABASE')) {
                Client::where('id', '!=', 0)->update(['sub_domain' => $request->sub_domain]);
            }
            return true;
        }
    }


    // ************* create connection with god panel database ******************************* /////////////////
    public function createConnectionToGodDb($id)
    {
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
    public function createConnectionToClientDb($db_name)
    {
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
        $task_proofs = TaskProof::where('type', '!=', 0)->get();
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
        $preference  = ClientPreference::where('client_id', Auth::user()->code)->first();
        $client      = Auth::user();
        $subClients  = SubClient::all();
        $smtp        = SmtpDetail::where('id', 1)->first();
        return view('configure')->with(['preference' => $preference, 'client' => $client,'subClients'=> $subClients,'smtp_details'=>$smtp]);
    }

    /**
     * Show Options page
     */
    public function ShowOptions()
    {
        $preference = ClientPreference::where('client_id', Auth::user()->id)->first();
        return view('options')->with(['preference' => $preference]);
    }

    public function cmsSave(Request $request, $id)
    {
        Cms::where('id', $id)->update(['content'=>$request->content]);
        return response()->json(true);
    }

    public function taskProof(Request $request)
    {
        $requestAll = $request->all();
        for ($i=1; $i <= 3 ; $i++) {
            $check = TaskProof::where('id', $i)->first();

            if (isset($check)) {
                $update                     = TaskProof::find($i);
            } else {
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
        $check = SmtpDetail::where('id', 1)->first();

        if (isset($check)) {
            $update                     = SmtpDetail::find(1);
        } else {
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
