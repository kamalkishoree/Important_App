<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Http\Request;
use App\Model\Countries; 
use \DateTimeZone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Jobs\UpdatePassword;
use Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $client = Client::where('id',1)->first();
        $countries = Countries::all();
       
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('profile')->with(['client' => $client ,'countries'=> $countries,'tzlist'=>$tzlist ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
       
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'company_name' => ['required'],
            'company_address' => ['required'],
        ]);
            
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update');
        }

        $getClient = Auth::user()->logo;
        $getFileName = $getClient;

        // Handle File Upload

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $s3filePath = '/assets/Clientlogo';
            //$file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            //$s3filePath = '/assets/Clientlogo/' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file,'public');
            $getFileName = $path;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'custom_domain' => $request->custom_domain,
            'country_id' => $request->country ? $request->country : NULL,
            'timezone' => $request->timezone ? $request->timezone : NULL,
            'logo' => $getFileName,
        ];

        $client = Client::where('code', $id)->update($data);
        $password = null;
        $this->dispatchNow(new UpdatePassword($password,$data));


        return redirect()->back()->with('success', 'Profile Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function displayImage()
    {
            $client = \Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $bucket = \Config::get('filesystems.disks.s3.bucket');

            //echo Auth::user()->logo;die;

            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => Auth::user()->logo  // file name in s3 bucket which you want to access
            ]);

            $request = $client->createPresignedRequest($command, '+20 minutes');
            
            $image = (string)$request->getUri();          

            //$file = File::get($path);

            //$type = File::mimeType($path);

            //$response = imagecreatefromfile($image);

            return \Image::make($image)->fit(90,50)->response('png');

    }

    
}
