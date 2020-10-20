<?php

namespace App\Http\Controllers;

use App\Model\Task;
use Illuminate\Http\Request;
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

        $tasks = Task::orderBy('created_at', 'DESC')->paginate(10);
        return view('tasks/task')->with(['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks/add-task');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'from_address' => ['required'],
            'to_address' => ['required']
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

        $data = [
            'name'                       => $request->name,
            'from_address'               => $request->from_address,
            'to_address'                 => $request->to_address,
            'status'                     => $request->status,
            'priority'                   => $request->priority,
            'expected_delivery_date'     => $request->expected_delivery_date
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
}
