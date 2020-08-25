<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Manager;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $managers = Manager::where('client_id',auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('manager')->with(['managers' => $managers]);
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
     * Validation method for manager data 
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','email'],
            'phone_number' => ['required'],
            'can_create_task' => ['required'],
            'can_edit_task_created' => ['required'],
            'can_edit_all' => ['required'],
            'can_manage_unassigned_tasks' => ['required'],
            'can_edit_auto_allocation' => ['required']
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
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'can_create_task' => $request->can_create_task,
            'can_edit_task_created' => $request->can_edit_task_created,
            'can_edit_all' => $request->can_edit_all,
            'can_manage_unassigned_tasks' => $request->can_manage_unassigned_tasks,
            'can_edit_auto_allocation' => $request->can_edit_auto_allocation,
            'profile_picture' => $getFileName,
            'client_id' => auth()->user()->id
        ];

        $manager = Manager::create($data);
        if($manager->wasRecentlyCreated){
            return response()->json([
                'status'=>'success',
                'message' => 'Manager created Successfully!',
                'data' => $manager
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
        //
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
