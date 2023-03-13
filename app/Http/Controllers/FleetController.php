<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\AgentFleet;
use App\Model\Fleet;
use App\Model\Order;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Carbon\Carbon;
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

        $agents = Fleet::with('getDriver')->orderBy('id', 'desc')->get();
        $fleets = AgentFleet::pluck('fleet_id');
        $agents = Fleet::select('id')->orderBy('id', 'desc');
        $all = $agents->count();
        $assigned = $agents->whereIn('id',$fleets)->count();
        $free = $all - $assigned;
        $drivers = Agent::select('id','name')->get();
        return view('fleets.index',compact('all','assigned','free','drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderFleetDetail(Request $request)
    {
        $order = Order::findOrFail($request->orderId);

        $table ='';
        $table .='<table class="table table-striped dt-responsive nowrap w-100 agents-datatable">'; 
            $table .='<tr>'; 
            $table .='<td>Order Number</td>'; 
            $table .='<td>'.$order->order_number.'</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Customer Name</td>'; 
            $table .='<td>'.$order->customer->name.'</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Customer Number</td>'; 
            $table .='<td>'.$order->customer->phone_number.'</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Task Pickup</td>'; 
            $table .='<td>'.$order->pickup_task[0]->location->address.'</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Task Dropoff</td>'; 
            $table .='<td>'.$order->dropoff_task[0]->location->address.'</td>'; 
            $table .='<tr>'; 


            $table .='</tr>'; 
            $table .='<td>Tracking url</td>'; 
            $table .='<td> <a href="'.url('/order/tracking/'.Auth::user()->code.'/'.$order->unique_id.'').'" target="_blank"><span id="pwd_spn" class="password-span">'.url('/order/tracking/'.Auth::user()->code.'/'.$order->unique_id.'').'</span></a></td>'; 
            $table .='</tr>'; 
        $table .='</table>'; 

        return $table;

    }


    public function carDetail(Request $request)
    {
        $fleet = Fleet::findOrFail($request->fleetId);
        if(isset($request->action) && $request->action =='edit')
        {
            return response($fleet);   
        }
        $table ='';
        $table .='<table class="table table-striped dt-responsive nowrap w-100 agents-datatable">'; 
            $table .='<tr>'; 
            $table .='<td>Fleet Registration No</td>'; 
            $table .='<td>'.$fleet->registration_name.'</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Fleet Name</td>'; 
            $table .='<td>'.$fleet->name.'</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Fleet Model(Year)</td>'; 
            $table .='<td>'.$fleet->model.' ('.$fleet->year.')</td>'; 
            $table .='<tr>'; 

            $table .='<tr>'; 
            $table .='<td>Total km/miles traveled by fleet</td>'; 
            $table .='<td>'.totalKmTravel($fleet->id).'</td>'; 
            $table .='<tr>'; 


        $table .='</table>'; 

        return $table;

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
                 'registration_name' => 'required|unique:fleets,registration_name,'.$request->editId,
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
                
                if(!empty($request->editId) && isset($request->editId)){
                    $agent = Fleet::find($request->editId);
                    $agent->update($data);
                }else{
                    $agent = Fleet::create($data);
                }

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
        $agentFleet = AgentFleet::where('agent_id',$request->agent_id)->first();
        // dd($agentFleet);
        if($agentFleet){
            // dd('ddd-ex'.$agentFleet->agent_id);

            AgentFleet::where('agent_id',$agentFleet->agent_id)->delete();
            // dd('ddd-ex'.$agentFleet->agent_id);

            $fleetUpdate = AgentFleet::create(['fleet_id'=>$request->fleet_id,'agent_id'=>$request->agent_id]);
        }else{
            AgentFleet::where('fleet_id',$request->fleet_id)->delete();
            if(@$request->agent_id){
                $fleetUpdate = AgentFleet::create(['fleet_id'=>$request->fleet_id,'agent_id'=>$request->agent_id]);
            }
        }
       return back()->with('success',"Fleet Updated successfully.");
    }

    public function fleetFilter(Request $request)
    {
        try {
            // $user = Auth::user();
            $fleets = AgentFleet::pluck('fleet_id');
            $agents = Fleet::orderBy('id', 'desc');
            if($request->status == '1')
            {
                $agents->whereIn('id',$fleets);
            }elseif($request->status == '2')
            {
                $agents->whereNotIn('id',$fleets);
            }

            if(!empty($request->fdate) && !empty($request->tdate))
            {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->fdate)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->tdate)->endOfDay();
                $agents->whereBetween('created_at',[$startDate,$endDate]);
            }

            if(!empty($request->driver))
            {
                $agents->whereHas('getDriver', function ($q)use ($request)
                    {
                            return $q->where('agent_id',$request->driver);
                    }
                );
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
                                        <button type="submit" class="btn btn-primary-outline"> <i class="mdi mdi-delete" agentid="'.$agents->id.'"></i></button>
                                    </div>
                                </form>
                                <button type="button" class="btn btn-primary-outline editFleet" data-fleet-id="'.$agents->id.'"> <i class="mdi mdi-square-edit-outline" ></i></button>
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
        $orders = Order::where('fleet_id',base64_decode($request->id))->get();
        return view('fleets.details',compact('orders'));
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
