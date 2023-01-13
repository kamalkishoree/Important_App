<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use Exception;
use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Model\AgentCashCollectPop;


class AgentThresholdController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(){
        $agents                     = AgentCashCollectPop::orderBy('created_at', 'DESC')->get();
        $AutomatcallyPayments       = count($agents->where('payment_type', '1'));
        $ManuallyPayments           = count($agents->where('payment_type', '0'));
        $ApprovedPayments           = count($agents->where('status', '1'));
        $PendingPayments            = count($agents->where('status', '0'));
        $RejectedPayments           = count($agents->where('status', '2'));
        $agentsCount                = count($agents);
        return view('agent_threshold.index')->with(['agentsCount'=>$agentsCount, 'AutomatcallyPayments'=>$AutomatcallyPayments, 'ManuallyPayments'=>$ManuallyPayments,'ApprovedPayments'=>$ApprovedPayments,'PendingPayments'=>$PendingPayments,'RejectedPayments'=>$RejectedPayments]);
   }

   public function ThresholdAgentFilter(Request $request){

    try {
       $agents = AgentCashCollectPop::with('agent')->orderBy('id', 'DESC')->get();
        return Datatables::of($agents)
            ->editColumn('name', function ($agents) use ($request) {
                if(isset($agents->agent) && !empty($agents->agent)){
                    $name = ucfirst($agents->agent->name);
                }else{
                    $name = '';
                }
                return $name;
            })
            ->editColumn('amount', function ($agents) use ($request) {
                return $agents->amount;
            })
            ->editColumn('transaction_id', function ($agents) use ($request) {
                return $agents->transaction_id;
            })
            ->editColumn('date', function ($agents) use ($request) {
                return $agents->date;
            })
            ->editColumn('payment_type', function ($agents) use ($request) {
               if($agents->payment_type ==1){
                return 'Automatcally';
               }
               elseif($agents->payment_type ==0){
                return 'Manually';
               }
            })
            ->editColumn('threshold_type', function ($agents) use ($request) {
                if($agents->threshold_type ==1){
                    return 'Day';
                }
                elseif($agents->threshold_type ==2){
                    return 'Week';
                }
                elseif($agents->threshold_type ==3){
                    return 'Month';
                }
            })
            ->editColumn('status', function ($agents) use ($request) {
                if($agents->status ==1){
                    return 'Approval';
                }
                elseif($agents->status ==0){
                    return 'Pending';
                }
                elseif($agents->status ==2){
                    return 'Rejected';
                }
            })
          
          
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                   
                    // $search = $request->get('search');
                    // $instance->where('uid', 'Like', '%'.$search.'%')
                    //     ->orWhere('name', 'Like', '%'.$search.'%')
                    //     ->orWhere('phone_number', 'Like', '%'.$search.'%')
                    //     ->orWhere('type', 'Like', '%'.$search.'%')
                    //     ->orWhere('created_at', 'Like', '%'.$search.'%')
                    //     ->orWhereHas('team', function($q) use($search){
                    //         $q->where('name', 'Like', '%'.$search.'%');
                    //     });
                }
            }, true)
            ->make(true);
    } catch (Exception $e) {
    }
   }

   public function export(){
     return '';
   }
}
