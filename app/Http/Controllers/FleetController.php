<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\AgentFleet;
use App\Model\Fleet;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FleetController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleets = AgentFleet::pluck('fleet_id');
        $agents = Fleet::select('id')->orderBy('id', 'desc');
        $all = $agents->count();
        $assigned = $agents->whereIn('id',$fleets)->count();
        $free = $all - $assigned;
        return view('fleets.index',compact('all','assigned','free'));
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
        try{

           $validator =  Validator::make($request->all(), [
                'name' => 'required',
                'make' => 'required',
                'model' => 'required',
                'registration_name' => 'required|unique:fleets',
                'year' => 'required',
                ],
                [
                    'registration_name.unique' => 'Need Unique Registration Name.',
                ]
            );
           
            if ( $validator->fails() ) 
            {
                return [
                    'success' => 0, 
                    'message' => $validator->errors()->first()
                ];
            }
            
                $data = [
                    'name' => $request->name,
                    'make' => $request->make,
                    'model' => $request->model,
                    'registration_name' => $request->registration_name,
                    'color' => $request->color,
                    'year' => $request->year,
                    'user_id' => auth()->id()
                ];
                $agent = Fleet::create($data);
                if ($agent){
                    $request->session()->flash('success','Thanks for Adding new Fleet.');
                    return response()->json([
                        'message' => 'Thanks for Adding new Fleet.',
                        'status' => 'success'
                    ]);
                }
        }catch(\Exception $e)
        {
            return response()->json([
                    'message'=> $e->getMessage(),
                    'status' => 'error'
            ]);
        }
    }
    public function assignDriver(Request $request)
    {
        $fleet = Fleet::where('id', $request->id)->select('id','name','registration_name')->first();
        $drivers = Agent::get();
        $check = 0;
        if(isset($fleet) && !empty($fleet->getDriver[0])){
            $check = $fleet->getDriver[0]->id;
        }

        $selected = '<select class="form-control" name="agent_id">
        <option value="">Not Assigned</option>';
        foreach($drivers as $driver)
         {
               $select = (($driver->id == $check)?'Selected':'');
               $selected .= '<option value="'.$driver->id.'" '.$select.'>'.$driver->name.'</option>';
        }
        $selected .= '</select>';

        return response()->json(array('success' => true, 'fleet' => $fleet,'agents'=>$selected));
    }

    public function updateDriver(Request $request)
    {
        AgentFleet::where('fleet_id',$request->fleet_id)->delete();
        if($request->agent_id){
            $fleetUpdate = AgentFleet::create(['fleet_id'=>$request->fleet_id,'agent_id'=>$request->agent_id]);
        }
       return back()->with('success',"Fleet Updated successfully.");
    }

    public function fleetFilter(Request $request)
    {
        try {
            $user = Auth::user();
            $fleets = AgentFleet::pluck('fleet_id');
            $agents = Fleet::with('getDriver')->orderBy('id', 'desc');
            if($request->status == '1')
            {
                $agents->whereIn('id',$fleets);
            }elseif($request->status == '2')
            {
                $agents->whereNotIn('id',$fleets);
            }
            // dd($agents->first()->created_at);
            return Datatables::of($agents)
                ->editColumn('name', function ($agents) {
                    $name =$agents->name;
                    return $name;
                })
                ->editColumn('model', function ($agents){
                    return $agents->model;
                })
                ->editColumn('make', function ($agents){
                    return $agents->make;
                })
                ->editColumn('registration_name', function ($agents) {
                    return __($agents->registration_name);
                })
                ->editColumn('year', function ($agents){
                    return __($agents->year);
                })
                ->editColumn('color', function ($agents){
                    return __($agents->color);
                })
                ->editColumn('driver', function ($agents){
                    return $agents->getDriver[0]->name??'N/A';
                })
                ->editColumn('created_at', function ($agents){
                    return date('d M, Y h:i:a',strtotime($agents->created_at));
                })
                ->editColumn('updated_at', function ($agents){
                    return $agents->updated_at??'';
                })
                ->editColumn('action', function ($agents){
                    $action = '<div class="inner-div">
                                <form id="agentdelete'.$agents->id.'" method="POST" action="' . route('fleet.destroy', $agents->id) . '">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                    <input type="hidden" name="_method" value="DELETE">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete" agentid="'.$agents->id.'"></i></button>
                                    </div>
                                </form>
                            </div>';
                    return $action;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                                 $search = $request->get('search');
                                 $instance->orWhere('name', 'Like', '%'.$search.'%')
                                    ->orWhere('registration_name', 'Like', '%'.$search.'%')
                                    ->orWhere('year', 'Like', '%'.$search.'%')
                                    ->orWhere('make', 'Like', '%'.$search.'%')
                                    ->orWhere('model', 'Like', '%'.$search.'%');
                    }
                }, true)
                ->make(true);
        } catch (Exception $e) {
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function show(Fleet $fleet)
    {
        //
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function fleetDetails(Request $request)
    {
        $fleet = Fleet::find(base64_decode($request->id));
        return view('fleets.details',compact('fleet'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function edit(Fleet $fleet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fleet $fleet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    { 
        Fleet::where('id', $id)->delete();
        AgentFleet::where('fleet_id',$id)->delete();
        return redirect()->back()->with('success',__('Fleet deleted successfully!'));
    }
}
