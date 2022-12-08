<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\AgentFleet;
use App\Model\Fleet;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use DataTables;
use Illuminate\Support\Facades\Auth;

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
        $fleets = Fleet::orderBy('id', 'DESC')->get();
        $drivers = Agent::get();
        // dd($fleets);
        return view('fleets.index',compact('fleets','drivers'));
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
              $request->validate([
                'name' => 'required',
                'make' => 'required',
                'model' => 'required',
                'regname' => 'required',
                'year' => 'required'
                ]);
            
                $data = [
                    'name' => $request->name,
                    'make' => $request->make,
                    'model' => $request->model,
                    'registration_name' => $request->regname,
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
    public function assignDriver(Request $request, $id)
    {
        $fleet = Fleet::where('id', $id)->first();
        $drivers = Agent::get();
        
        return response()->json(array('success' => true, 'fleet' => $fleet,'drivers'=>$drivers));
    }

    public function fleetFilter(Request $request)
    {
        try {
            $user = Auth::user();
            $agents = Fleet::with('getDriver')->orderBy('id', 'desc');
            if($user->id > 1)
            {
                $agents->where('user_id',$user->id);
            }
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
                    return $agents->getDriver[0]->name??'Assign Driver';
                })
                ->editColumn('action', function ($agents){
                    // $approve_action = '';
                    // if($agents->status == 'D'){
                    //     $approve_action .= '<div class="inner-div agent_approval_button" data-agent_id="'.$agents->id.'" data-status="1" title="Approve"><i class="fas fa-user-check" style="color: green; cursor:pointer;"></i></div><div class="inner-div ml-1 agent_approval_button" data-agent_id="'.$agents->id.'" data-status="2" title="Reject"><i class="fa fa-user-times" style="color: red; cursor:pointer;"></i></div>';
                    // } else if($agents->status == 'A'){
                    //     $approve_action .= '<div class="inner-div agent_approval_button" data-agent_id="'.$agents->id.'" data-status="1" title="Approve"><i class="fas fa-user-check" style="color: green; cursor:pointer;"></i></div>';
                    // }
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
