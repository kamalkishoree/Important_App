<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\Order;
use App\Model\Task;
use App\Model\Team;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if(isset($request->date)){
           $date =  $request->date;
        }else{
            $date = \Carbon\Carbon::today();
        }
        
        $teams  = Team::with([
            'agents.order'=> function($o) use ($date){
                $o->whereDate('order_time',$date)->with('customer')->with('task.location');
            }]
            )->get()->toArray();
            
        $unassigned = Agent::where('team_id',null)->with(['order'=> function($o) use ($date){
            $o->whereDate('order_time',$date)->with('customer')->with('task.location');
        }])->get()->toArray();
         
       // $unassignedTask = Order::where('order_time',$date)->with('task.location')->get();
        
       // dd($teams);
            
        $newmarker = [];
        foreach ($teams as $key => $team) {
            $append = [];
            $append[0] = $team['id'];
            $busycounter = 0;
            $inactivecounter = 0;
            foreach ($team['agents'] as $key => $agent) {
                // dd($agent);
                if(count($agent['order']) >0){
                    $busycounter++;
                }
                if($agent['is_available'] == 0){
                    $inactivecounter++;
                }
                
                $append[1] = $agent['id'];
                foreach ($agent['order'] as $key => $orders) {
                    foreach ($orders['task'] as $key => $tasks) {
                        if($tasks['task_type_id'] == 1){
                            $name = 'Pickup';
                        }elseif($tasks['task_type_id'] == 2){
                            $name = 'Drop';
                        }else{
                            $name = 'Appointment';
                        }
                        $append[2]  = $tasks['id']; 
                        $append[3]  = floatval($tasks['location']['latitude']);
                        $append[4]  = floatval($tasks['location']['longitude']);
                        $append[5]  = (int)$tasks['task_status'];
                        $append[6]  = $tasks['task_type_id'];
                        $append[7]  = $agent['name'];
                        $append[8]  = $tasks['location']['address'];
                        $append[9]  = $orders['customer']['name'];
                        $append[10] = $orders['customer']['phone_number'];
                        $append[11] = $name;
                        array_push($newmarker,$append);
                    }
                }
                
            }
            // $team['busycount']     = $busycounter;
            // $team['inactivecount'] = $inactivecounter;
            
        }

        
        
           
        foreach ($unassigned as $key => $agent) {
                $append = [];
                $append[0] = 0;
                $append[1] = $agent['id'];
                foreach ($agent['order'] as $key => $orders) {
                    foreach ($orders['task'] as $key => $tasks) {

                        if($tasks['task_type_id'] == 1){
                            $name = 'Pickup';
                        }elseif($tasks['task_type_id'] == 2){
                            $name = 'Drop';
                        }else{
                            $name = 'Appointment';
                        }

                        $append[2]  = $tasks['id']; 
                        $append[3]  = floatval($tasks['location']['latitude']);
                        $append[4]  = floatval($tasks['location']['longitude']);
                        $append[5]  = (int)$tasks['task_status'];
                        $append[6]  = $tasks['task_type_id'];
                        $append[7]  = $agent['name'];
                        $append[8]  = $tasks['location']['address'];
                        $append[9]  = $orders['customer']['name'];
                        $append[10] = $orders['customer']['phone_number'];
                        $append[11] = $name;
                        array_push($newmarker,$append);
                    }
                }
                
        }

            
        
        $theme = \App\Model\ClientPreference::where(['id' => 1])->first('theme');
       
       
        $agents = Agent::with('agentlog')->get()->toArray();

        return view('dashboard')->with(['teams' => $teams,'newmarker'=> $newmarker,'unassigned'=> $unassigned,'agents'=> $agents,'theme' => $theme]);
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
