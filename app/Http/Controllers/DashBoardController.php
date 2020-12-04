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
    public function index()
    {
        $teams  = Team::with('agents.order.task.location')->get();
        $newmarker = [];
        foreach ($teams as $key => $team) {
            $append = [];
            $append[0] = $team->id;
            foreach ($team->agents as $key => $agent) {
                $append[1] = $agent->id;
                foreach ($agent->order as $key => $orders) {
                    foreach ($orders->task as $key => $tasks) {
                        $append[2] = $tasks->id; 
                        $append[3] = floatval($tasks->location->latitude);
                        $append[4] = floatval($tasks->location->longitude);
                        $append[5] = $tasks->task_status;
                        array_push($newmarker,$append);
                    }
                }
                
            }
            
        }
       
       
        //$agents = Agent::with('order.task')->get();
        
        return view('dashboard')->with(['teams' => $teams,'newmarker'=> $newmarker]);
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
