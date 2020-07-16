<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
        return view('client')->with(['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('update-client');
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
            'database_name' => $request->database_name,
            'database_username' => $request->database_username,
            'database_password' => $request->database_password,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'custom_domain' => $request->custom_domain,
            'logo' => $getFileName,
        ];

        $client = Client::create($data);
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
        return view('update-client')->with('client', $client);
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
            'logo' => $getFileName,
        ];

        $client = Client::where('id', $id)->update($data);
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
}