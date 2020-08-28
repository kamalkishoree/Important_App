<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Agent;
use App\Model\Team;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = Agent::orderBy('created_at', 'DESC')->get();
        return view('agent')->with(['agents' => $agents]);
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
     * Validation method for agents data 
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'team_id' => ['required'],
            'type' => ['required'],
            'vehicle_type_id' => ['required'],
            'make_model' => ['required'],
            'plate_number' => ['required'],
            'phone_number' => ['required'],
            'color' => ['required'],
            'profile_picture' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
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
        $getFileName = NULL;
        // Handle File Upload
        if($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); 
            $fileNameToStore = $filename.'_'.time().'.'.$file->getClientOriginalExtension();  
            $file->move(public_path().'/agents',$fileNameToStore);
            $getFileName = $fileNameToStore;
        }
           
        $data = [
            'name' => $request->name,
            'team_id' => $request->team_id,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'plate_number' => $request->plate_number,
            'phone_number' => $request->phone_number,
            'color' => $request->color,
            'profile_picture' => $getFileName
        ];

        $agent = Agent::create($data);
        if($agent->wasRecentlyCreated){
            return response()->json([
                'status'=>'success',
                'message' => 'Agent created Successfully!',
                'data' => $agent
            ]);
        }
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
        $agent = Agent::find($id);
        $teams = Team::where('client_id',auth()->user()->id)->get();
        return view('update-agent')->with([
            'agent' => $agent,
            'teams' => $teams
        ]);
    }

    /**
     * Validation method for agent Update 
    */
    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'team_id' => ['required'],
            'type' => ['required'],
            'vehicle_type_id' => ['required'],
            'make_model' => ['required'],
            'plate_number' => ['required'],
            'phone_number' => ['required'],
            'color' => ['required'],
            'profile_picture' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
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

        $getAgent = Agent::find($id);
        $getFileName = $getAgent->profile_picture;

        if($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); 
            $fileNameToStore = $filename.'_'.time().'.'.$file->getClientOriginalExtension();  
            $file->move(public_path().'/agents',$fileNameToStore);
            $getFileName = $fileNameToStore;
        }

        $data = [
            'name' => $request->name,
            'team_id' => $request->team_id,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'plate_number' => $request->plate_number,
            'phone_number' => $request->phone_number,
            'color' => $request->color,
            'profile_picture' => $getFileName
        ];
        
        $agent = Agent::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Agent Updated successfully!');
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
}