<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\Customer;
use App\Model\Location;
use App\Model\Order;
use App\Model\Task;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = \Carbon\Carbon::today();

   
        $totalearning       = Order::whereDate('order_time', $date)->sum('order_cost');
        $totalagentearning  = Order::whereDate('order_time', $date)->sum('driver_cost');
        $totalorders        = Order::whereDate('order_time', $date)->count();
        $totalagents        = Agent::count();

        $agents             = Agent::orderBy('cash_at_hand','DESC')->limit(5)->get();
        $customers          = Customer::withCount('orders')->orderBy('orders_count','DESC')->limit(5)->get();
        $heatLatLog         = Location::whereIn('id', function($query) use($date){
                              $query->select('location_id')
                              ->from(with(new Task)->getTable())
                              ->whereDate('created_at',$date);
                              })->get();
    
        
        //     // for each day in the month
        // for($i = 1; $i <=  date('t'); $i++)
        // {
        //     // add the date to the dates array
        //     $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT) . " " . date('M') . " " . date('Y');
        // }

        // // show the dates array
        // print_r($dates);
        // die;
       
        return view('accounting',compact('totalearning','totalagentearning','totalorders','totalagents','agents','customers','heatLatLog'));
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
