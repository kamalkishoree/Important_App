<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use Session;
use DataTables;
use Carbon\Carbon;
// use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
// use App\Http\Traits\ToasterResponser;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\OrderVendorListExport;
use App\Http\Controllers\BaseController;
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout};

class AgentPayoutController extends BaseController{
    use ApiResponser;
    // use ToasterResponser;
    public $gateway;
    public $currency;

    public function __construct(){
        // $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        // if($stripe_creds){
        //     $creds_arr = json_decode($stripe_creds->credentials);
        //     $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        //     $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;
        //     $this->gateway = Omnipay::create('Stripe');
        //     $this->gateway->setApiKey($api_key);
        //     $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        // }

    }

    public function filter(Request $request){
        $from_date = "";
        $to_date = "";
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
        }
        $agents = Agent::with(['orders' => function($query) use($from_date,$to_date) {
            if((!empty($from_date)) && (!empty($to_date))){
                $query->between($from_date." 00:00:00", $to_date." 23:59:59");
            }
        }])->where('status', '!=', '2')->orderBy('id', 'desc');

        $agents = $agents->get();
        foreach ($agents as $agent) {
            $agent->total_paid = 0.00;
            $agent->view_url = route('agent.show', $agent->id);
            $agent->payable_amount = number_format($agent->orders->sum('order_cost'),2, ".","");
            $agent->admin_commission_amount = number_format($agent->orders->sum('admin_commission_percentage_amount') + $agent->orders->sum('admin_commission_fixed_amount'), 2, ".","");

            $is_stripe_connected = 0;
            // $checkIfStripeAccountExists = AgentConnectedAccount::where('agent_id', $agent->id)->first();
            // if($checkIfStripeAccountExists && (!empty($checkIfStripeAccountExists->account_id))){
            //     $is_stripe_connected = 1;
            // }
            $agent->is_stripe_connected = $is_stripe_connected;

            $agent->agent_earning = number_format($agent->order_value, 2, ".","");
        }
        return Datatables::of($agents)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request){
                        if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                            return true;
                        }
                        return false;
                    });
                }
            })->make(true);
    }

    // public function export() {
    //     return Excel::download(new OrderVendorListExport, 'vendor_list.xlsx');
    // }


    public function agentPayoutRequests(Request $request)
    {        
        $user = Auth::user();
        $total_order_value = Order::orderBy('id','desc');
        if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', $user->id);
            });
        }
        $total_order_value = $total_order_value->sum('order_cost');

        $pending_payouts = AgentPayout::where('status', 0);
        $completed_payouts = AgentPayout::whereIn('status', [1,2]);
        $pending_payout_value = $pending_payouts->sum('amount');
        $completed_payout_value = $completed_payouts->sum('amount');
        $pending_payout_count = $pending_payouts->count();
        $completed_payout_count = $completed_payouts->count();
        $payout_options = PayoutOption::where('status', 1)->get();
        $preferences = ClientPreference::with('currency')->select('currency_id')->where('id', 1)->first();
        $currency_symbol = $preferences->currency->symbol ?? '$';

        return view('agent.payout-requests')->with(['total_order_value' => number_format($total_order_value, 2), 'pending_payout_value'=>$pending_payout_value, 'completed_payout_value'=>$completed_payout_value, 'pending_payout_count'=>$pending_payout_count, 'completed_payout_count'=>$completed_payout_count, 'payout_options'=>$payout_options, 'currency_symbol'=>$currency_symbol]);
    }

    public function vendorPayoutRequestsFilter(Request $request){
        $from_date = "";
        $to_date = "";
        $user = Auth::user();
        $status = $request->status;
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
        }
        $vendor_payouts = AgentPayout::with(['agent', 'payoutOption'])->orderBy('id','desc');
        // if($user->is_superadmin == 0){
        //     $vendor_payouts = $vendor_payouts->whereHas('vendor.permissionToUser', function ($query) use($user) {
        //         $query->where('user_id', $user->id);
        //     });
        // }
        $vendor_payouts = $vendor_payouts->where('status', $status)->get();
        foreach ($vendor_payouts as $payout) {
            $payout->date = Carbon::parse($payout->created_at)->toDateTimeString();
            $payout->agentName = $payout->agent->name;
            // $payout->requestedBy = ucfirst($payout->user->name);
            $payout->amount = $payout->amount;
            $payout->type = $payout->payoutOption->title;
        }
        return Datatables::of($vendor_payouts)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                // if (!empty($request->get('search'))) {
                //     $instance->collection = $instance->collection->filter(function ($row) use ($request){
                //         if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                //             return true;
                //         }
                //         return false;
                //     });
                // }
            })->make(true);
    }

    public function vendorPayoutRequestComplete(Request $request, $domain = '', $id){
        try{
            DB::beginTransaction();
            $payout = AgentPayout::where('id', $id)->first();
            $user = Auth::user();
            $agent_id = $payout->agent_id;

            $total_delivery_fees = Order::where('driver_id', $agent_id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0) {
            //     $total_delivery_fees = $total_delivery_fees->whereHas('vendor.permissionToUser', function ($query) use($user) {
            //         $query->where('user_id', $user->id);
            //     });
            // }
            $total_delivery_fees = $total_delivery_fees->sum('distance_fee');

            $total_promo_amount = Order::where('driver_id', $agent_id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0) {
            //     $total_promo_amount = $total_promo_amount->whereHas('vendor.permissionToUser', function ($query) use($user) {
            //         $query->where('user_id', $user->id);
            //     });
            // }
            $total_promo_amount = $total_promo_amount->where('coupon_paid_by', 0)->sum('discount_amount');

            $total_admin_commissions = Order::where('driver_id', $agent_id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0) {
            //     $total_admin_commissions = $total_admin_commissions->whereHas('vendor.permissionToUser', function ($query) use($user) {
            //         $query->where('user_id', $user->id);
            //     });
            // }
            $total_admin_commissions = $total_admin_commissions->sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));

            $total_order_value = Order::where('driver_id', $agent_id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0) {
            //     $total_order_value = $total_order_value->whereHas('vendor.permissionToUser', function ($query) use($user) {
            //         $query->where('user_id', $user->id);
            //     });
            // }
            $total_order_value = $total_order_value->sum('order_cost') - $total_delivery_fees;

            $vendor_payouts = AgentPayout::where('agent_id', $agent_id)->orderBy('id','desc');
            // if($user->is_superadmin == 0){
            //     $vendor_payouts = $vendor_payouts->whereHas('vendor.permissionToUser', function ($query) use($user) {
            //         $query->where('user_id', $user->id);
            //     });
            // }
            $vendor_payouts = $vendor_payouts->where('status', 1)->sum('amount');

            $past_payout_value = $vendor_payouts;
            $available_funds = $total_order_value - $total_admin_commissions - $total_promo_amount - $past_payout_value;

            if($request->amount > $available_funds){
                $toaster = $this->errorToaster('Error', __('Payout amount is greater than agent available funds'));
                return Redirect()->back()->with('toaster', $toaster);
            }

            $payout->status = 1;
            $payout->save();
            DB::commit();
            $toaster = $this->successToaster(__('Success'), __('Payout has been completed successfully'));
        }
        catch(Exception $ex){
            DB::rollback();
            $toaster = $this->errorToaster(__('Errors'), $ex->message());
        }
        return Redirect()->back()->with('toaster', $toaster);
    }
}
