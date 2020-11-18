<?php

namespace App\Http\Controllers;

use App\Model\Task;
use App\Model\Location;
use App\Model\Customer;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;
use Illuminate\Http\Request;
use App\Model\Agent;
use App\Model\Order;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'DESC')->with('order.customer')->paginate(10);
       
        return view('tasks/task')->with(['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamTag    = TagsForTeam::all();
        $agentTag   = TagsForAgent::all();
        $agents = Agent::orderBy('created_at', 'DESC')->get();
        return view('tasks/add-task')->with(['teamTag'=>$teamTag,'agentTag'=>$agentTag,'agents' => $agents]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
           
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
        $loc_id = 0;
        $cus_id = 0;
        if(!isset($request->ids)){
          $cus = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
          ];
          $customer = Customer::create($cus);
          $cus_id = $customer->id;
        }else{
            $cus_id = $request->ids;
        }
       
        if(!isset($request->old_address_id)){
                $loc = [
                    'short_name' => $request->short_name,
                    'address'    => $request->address,
                    'post_code'  => $request->post_code,
                    'created_by' => $cus_id,
                ];
                $Loction = Location::create($loc);
                $loc_id = $Loction->id;
        }else{
                $loc_id = $request->old_address_id;
        }

        $order = [
            'customer_id'                => $cus_id,
            'recipient_phone'            => $request->recipient_phone,
            'Recipient_email'            => $request->recipient_email,
            'task_description'           => $request->task_description,
            'driver_id'                  => $request->agent,
        ];
        $orders = Order::create($order);


        $data = [
            'order_id'                   => $orders->id,
            'task_type_id'               => $request->task_type_id,
            'location_id'                => $loc_id,
        ];

        $task = Task::create($data);


        return redirect()->route('tasks.index')->with('success', 'Task Added successfully!');
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
        $task = Task::where('id',$id)->first();
        return view('tasks/update-task')->with(['task'=>$task]);
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
        $validator = $this->validator($request->all())->validate();

        $getAgent = Task::find($id);
        


        $data = [
            'name'                       => $request->name,
            'from_address'               => $request->from_address,
            'to_address'                 => $request->to_address,
            'status'                     => $request->status,
            'priority'                   => $request->priority,
            'expected_delivery_date'     => $request->expected_delivery_date
        ];
        
        $agent = Task::where('id', $id)->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function search(Request $request)
    {
       
        $search = $request->search;
        if(isset($search)){
        if($search == ''){
         $employees = Customer::orderby('name','asc')->select('id','name')->limit(10)->get();
        }else{
         $employees = Customer::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(10)->get();
        } 

        $response = array();
        foreach($employees as $employee){
         $response[] = array("value"=>$employee->id,"label"=>$employee->name);
        }


      return response()->json($response);
       }else{
        $id = $request->id;
        $loction = Location::where('created_by',$id)->get();
        return response()->json($loction);
       }
        
    }
}
