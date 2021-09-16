<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Agent;
use App\Model\AgentPayment;
use App\Model\DriverGeo;
use App\Model\Order;
use App\Model\Otp;
use App\Model\Team;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;
use App\Model\Countries;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $agents = Agent::orderBy('id', 'DESC');
        if(!empty($request->date)){
            $agents->whereBetween('created_at', [$request->date." 00:00:00",$request->date." 23:59:59"]);
        }
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $agents = $agents->paginate(10);
       

        $tags  = TagsForAgent::all();
        $tag   = [];
        foreach ($tags as $key => $value) {
            array_push($tag, $value->name);
        }
        $teams  = Team::where('client_id', auth()->user()->code)->orderBy('name');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $teams        = $teams->get();
        $selectedDate = !empty($request->date) ? $request->date : '';
        $tags         = TagsForTeam::all();

        $getAdminCurrentCountry = Countries::where('id', '=', Auth::user()->country_id)->get()->first();
        if(!empty($getAdminCurrentCountry)){
            $countryCode = $getAdminCurrentCountry->code;
        }else{
            $countryCode = '';
        }

        return view('agent.index')->with(['agents' => $agents,'teams'=>$teams, 'tags' => $tags, 'selectedCountryCode' => $countryCode, 'calenderSelectedDate'=>$selectedDate, 'showTag' => implode(',', $tag)]);
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
          $full_number = '';
          if(isset($data['country_code']) && !empty($data['country_code']) && isset($data['phone_number']) && !empty($data['phone_number']))
          $full_number = '+'.$data['country_code'].$data['phone_number'];

          $data['phone_number'] = '+'.$data['country_code'].$data['phone_number'];
          return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
            'vehicle_type_id' => ['required'],
            //'make_model' => ['required'],
            //'plate_number' => ['required'],
            'phone_number' =>  ['required', 'min:9','max:15',Rule::unique('agents')->where(function ($query) use ($full_number) {
                return $query->where('phone_number', $full_number);
            })],
            //'color' => ['required'],
            'profile_picture' => ['mimes:jpeg,png,jpg,gif,svg|max:2048'],
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
        $getFileName = null;
        // $full_number = '+'.$request->country_code.$request->phone_number;
        // if(isset($full_number) && !empty($full_number)){
        //     $already = Agent::where('phone_number',$full_number)->count();
        //     if($already > 0){
        //         return response()->json([
        //             'status'=>'error',
        //             'message' => 'The Phone number is already exist!',
        //             'data' => []
        //         ]);
        //     }
            
        // }
      
       

        $newtag = explode(",", $request->tags);
        $tag_id = [];
        foreach ($newtag as $key => $value) {
            if (!empty($value)) {
                $check = TagsForAgent::firstOrCreate(['name' => $value]);
                array_push($tag_id, $check->id);
            }
        }

        // Handle File Upload
        if ($request->hasFile('profile_picture')) {
            $folder = str_pad(Auth::user()->code, 8, '0', STR_PAD_LEFT);
            $folder = 'client_'.$folder;
            $file = $request->file('profile_picture');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $s3filePath = '/assets/'.$folder.'/agents' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $getFileName = $path;
        }
           
        $data = [
            'name' => $request->name,
            'team_id' => $request->team_id == null ? $team_id = null:$request->team_id,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'type' => $request->type,
            'vehicle_type_id' => $request->vehicle_type_id,
            'make_model' => $request->make_model,
            'plate_number' => $request->plate_number,
            'phone_number' => '+'.$request->country_code.$request->phone_number,
            'color' => $request->color,
            'profile_picture' => $getFileName != null ? $getFileName : 'assets/client_00000051/agents5fedb209f1eea.jpeg/Ec9WxFN1qAgIGdU2lCcatJN5F8UuFMyQvvb4Byar.jpg',
            'uid' => $request->uid,
            'is_approved' => 1
        ];

        $agent = Agent::create($data);
        $agent->tags()->sync($tag_id);

        if ($agent->wasRecentlyCreated) {
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
        $agent = Agent::with(['tags'])->where('id', $id)->first();
        $teams = Team::where('client_id', auth()->user()->code);
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $teams = $teams->get();

        $tags  = TagsForAgent::all();
        $uptag   = [];
        foreach ($tags as $key => $value) {
            array_push($uptag, $value->name);
        }

        $tagIds = [];
        foreach ($agent->tags as $tag) {
            $tagIds[] = $tag->name;
        }
        $date = Date('Y-m-d H:i:s');
        
        $otp = Otp::where('phone', $agent->phone_number)->where('valid_till', '>=', $date)->first();
        if (isset($otp)) {
            $send_otp = $otp->opt;
        } else {
            $send_otp = 'View OTP after Logging in the Driver App';
        }
        
        $returnHTML = view('agent.form')->with(['agent' => $agent, 'teams' => $teams, 'tags' => $uptag, 'tagIds' => $tagIds,'otp'=>$send_otp])->render();
        
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Validation method for agent Update
    */
    protected function updateValidator(array $data,$id)
    {    $full_number = '';
        $full_number = $data['phone_number'];
       
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required'],
            'vehicle_type_id' => ['required'],
            //'make_model' => ['required'],
            //'plate_number' => ['required'],
            'phone_number' => ['required', 'min:9','max:15',Rule::unique('agents')->where(function ($query) use ($full_number,$id) {
                return $query->where('phone_number', $full_number)->where('id','!=',$id);
            })],
            //'color' => ['required'],
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
    public function update(Request $request, $domain = '', $id)
    {
        $validator = $this->updateValidator($request->all(),$id)->validate();
        
         $agent = Agent::findOrFail($id);
        // if(isset($request->phone_number) && !empty($request->phone_number)){
        //     $already = Agent::where('phone_number',$request->phone_number)->where('id','!=',$id)->count();
        //     if($already > 0){
        //         return response()->json([
        //             'status'=>'error',
        //             'message' => 'The Phone number is already exist!',
        //             'data' => []
        //         ]);
        //     }
            
        // }
        $getFileName = $agent->profile_picture;

        $newtag = explode(",", $request->tags);

        $tag_id = [];

        foreach ($newtag as $key => $value) {
            if (!empty($value)) {
                $check = TagsForAgent::firstOrCreate(['name' => $value]);
                array_push($tag_id, $check->id);
            }
        }

        //handal image upload
        if ($request->hasFile('profile_picture')) {
            $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
            $folder = 'client_'.$folder;
            $file = $request->file('profile_picture');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $s3filePath = '/assets/'.$folder.'/agents' . $file_name;
            $path = Storage::disk('s3')->put($s3filePath, $file, 'public');
            $getFileName = $path;
        }

        foreach ($request->only('name', 'type', 'vehicle_type_id', 'make_model', 'plate_number', 'phone_number', 'color', 'uid') as $key => $value) {
            $agent->{$key} = $value;
        }
        $agent->team_id         = $request->team_id;
        $agent->profile_picture = $getFileName;
        $agent->save();

        $agent->tags()->sync($tag_id);
        
        if ($agent) {
            return response()->json([
                'status'=>'success',
                'message' => 'Agent updated Successfully!',
                'data' => $agent
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        DriverGeo::where('driver_id', $id)->delete();  // i have to fix it latter
        Agent::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Agent deleted successfully!');
    }

    public function payreceive(Request $request, $domain = '')
    {
        $data = [
            'driver_id' => $request->driver_id,
            'cr' => $request->payment_type == 1 ? $request->amount:null,
            'dr' => $request->payment_type == 2 ? $request->amount:null,
        ];
        
        $agent = AgentPayment::create($data);

        return response()->json(true);
    }

    public function agentPayDetails($domain = '', $id)
    {
        $data = [];
        $agent = Agent::where('id', $id)->first();
        if (isset($agent)) {
            $cash  = $agent->order->sum('cash_to_be_collected');
            $driver_cost  = $agent->order->sum('driver_cost');
            $order = $agent->order->sum('order_cost');
            $credit = $agent->agentPayment->sum('cr');
            $debit = $agent->agentPayment->sum('dr');
        } else {
            $cash  = 0;
            $order = 0;
            $driver_cost = 0;
            $credit = 0;
            $debit = 0;
        }
      
        $data['cash_to_be_collected'] = $cash;
        $data['order_cost']           = $order;
        $data['driver_cost']           = $driver_cost;
        $data['credit']           = $credit;
        $data['debit']           = $debit;
       
      
        return response()->json($data);
    }

    /* Change Agent approval status */

    public function approval_status(Request $request)
    {
        try {
            $agent_approval = Agent::find($request->id);
            $agent_approval->is_approved = $request->is_approved;
            $agent_approval->save();
            return response()->json(['status' => 1, 'message' => 'Status change successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

}
