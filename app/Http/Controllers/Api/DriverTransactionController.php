<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Model\{Agent, AgentPayment, TagsForAgent};

class DriverTransactionController extends Controller
{
    public function transactionDetails($id)
    {
        $data = [];
        $agent = Agent::where('id', $id)->first();
        if ($agent) {
            $payments = AgentPayment::where("driver_id", $id)->get();
            $cash  = $agent->order->sum('cash_to_be_collected');
            $driver_cost  = $agent->order->sum('driver_cost');
            $order_cost = $agent->order->sum('order_cost');
            $credit = $agent->agentPayment->sum('cr');
            $debit = $agent->agentPayment->sum('dr');
            $balance = ($debit - $credit) - ($cash - $driver_cost);
            $final_balance = number_format($balance, 2, '.', '');
            $payments = $payments;
        } else {
            $cash  = 0;
            $order = 0;
            $driver_cost = 0;
            $credit = 0;
            $debit = 0;
            $payments = [];
            $final_balance = 0.00;
        }
        $data['debit'] = $debit;
        $data['credit'] = $credit;
        $data['order_cost'] = $order_cost;
        $data['driver_cost'] = $driver_cost;
        $data['cash_to_be_collected'] = $cash;
        $data['final_balance'] = $final_balance;
        $data['payments'] = $payments;

        return response()->json($data);
    }
}
