<?php

namespace App\Http\Controllers\Api;
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
use App\Http\Controllers\Api\BaseController;
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

    public function agentPayoutRequestCreate(Request $request, $id){
        try{
            DB::beginTransaction();
            $agent = Agent::where('id',$id);
            // $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
            $user = Auth::user();
            // if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            //     $agent = $agent->whereHas('team.permissionToManager', function ($query) use($user) {
            //         $query->where('sub_admin_id', $user->id);
            //     });
            // }
            $agent = $agent->first();
            $credit = $agent->agentPayment->sum('cr');
            $debit = $agent->agentPayment->sum('dr');

            $total_order_value = Order::where('driver_id', $agent->id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            //     $agent = $agent->whereHas('team.permissionToManager', function ($query) use($user) {
            //         $query->where('sub_admin_id', $user->id);
            //     });
            // }
            $total_order_value = $total_order_value->sum('order_cost');

            $total_order_value = Order::where('driver_id', $agent->id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            //     $agent = $agent->whereHas('team.permissionToManager', function ($query) use($user) {
            //         $query->where('sub_admin_id', $user->id);
            //     });
            // }
            $total_order_value = $total_order_value->sum('order_cost');

            $agent_payouts = AgentPayout::where('agent_id', $agent->id)->orderBy('id','desc');
            // if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
            //     $agent = $agent->whereHas('team.permissionToManager', function ($query) use($user) {
            //         $query->where('sub_admin_id', $user->id);
            //     });
            // }
            $agent_payouts = $agent_payouts->sum('amount');

            $past_payout_value = $agent_payouts;
            $available_funds = $total_order_value + $agent->balanceFloat + $debit - $past_payout_value - $credit;

            if($request->amount > $available_funds){
                return $this->error(__('Payout amount is greater than available funds'), 402);
            }

            $preferences = ClientPreference::select('currency_id')->where('id', 1)->first();

            $pay_option = $request->payment_option_id ?? 1;

            $payout = new AgentPayout();
            $payout->agent_id = $id;
            $payout->payout_option_id = $pay_option;
            $payout->transaction_id = ($pay_option != 1) ? $request->transaction_id : '';
            $payout->amount = $request->amount;
            $payout->currency = $preferences->currency_id;
            $payout->requested_by = $agent->id;
            $payout->status = 0;
            $payout->save();
            DB::commit();
            return $this->success('', __('Payout is created successfully'), 201);
        }
        catch(Exception $ex){
            DB::rollback();
            return $this->error($ex->getMessage(), $ex->getCode());
        }
    }


    /**   show agent payout tab details   */
    public function agentPayoutDetails(Request $request, $id)
    {
        $agent = Agent::where('id',$id);
        // $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $user = Auth::user();
        // if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
        //     $agent = $agent->whereHas('team.permissionToManager', function ($query) use($user) {
        //         $query->where('sub_admin_id', $user->id);
        //     });
        // }
        $agent = $agent->first();

        $client_preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();

        $total_order_value = Order::where('driver_id', $id)->orderBy('id','desc');
        // if ($user->is_superadmin == 0 && $user->all_team_access == 0) {
        //     $agent = $agent->whereHas('team.permissionToManager', function ($query) use($user) {
        //         $query->where('sub_admin_id', $user->id);
        //     });
        // }
        $total_order_value = $total_order_value->sum('order_cost');

        $agent_payouts = AgentPayout::where('agent_id', $id)->orderBy('id','desc');
        // if($user->is_superadmin == 0){
        //     $agent_payouts = $agent_payouts->whereHas('vendor.permissionToUser', function ($query) use($user) {
        //         $query->where('user_id', $user->id);
        //     });
        // }
        $agent_payouts = $agent_payouts->where('status', 1)->sum('amount');

        $past_payout_value = $agent_payouts;

        $available_funds = $total_order_value + $agent->balanceFloat + $debit - $past_payout_value - $credit;
        // $available_funds = number_format($available_funds, 2, '.', ',');
        $past_payout_value = number_format($past_payout_value, 2, '.', ',');

        //stripe connected account details
        $stripe_connect_url = '';
        $codes = ['stripe'];
        $payout_creds = PayoutOption::whereIn('code', $codes)->where('status', 1)->first();
        if(!empty($payout_creds->credentials)){
            $creds_arr = json_decode($payout_creds->credentials);
            $client_id = (isset($creds_arr->client_id)) ? $creds_arr->client_id : '';
        }
        // $test_mode = (isset($paylink_creds->test_mode) && ($paylink_creds->test_mode == '1')) ? true : false;
        // $client = Session::has('client_config') ? Session::get('client_config')->code : '';

        $payout_options = PayoutOption::where('status', 1)->get();

        $is_stripe_connected = 0;
        // $checkIfStripeAccountExists = VendorConnectedAccount::where('vendor_id', $id)->first();
        // if($checkIfStripeAccountExists && (!empty($checkIfStripeAccountExists->account_id))){
        //     $is_stripe_connected = 1;
        // }
        $server_url = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/";
        $stripe_redirect_url = $server_url."client/verify/oauth/token/stripe";

        if((!empty($payout_creds->credentials)) && ($client_id != '')){
            $stripe_connect_url = 'https://connect.stripe.com/oauth/v2/authorize?response_type=code&state='.$id.'&client_id='.$client_id.'&scope=read_write&redirect_uri='.$stripe_redirect_url;
        }

        $taxCate = TaxCategory::all();
        return view('backend.vendor.vendorPayout')->with(['client_preferences' => $client_preferences, 'agent' => $agent, 'stripe_connect_url'=> $stripe_connect_url, 'is_payout_enabled'=>$this->is_payout_enabled, 'is_stripe_connected'=>$is_stripe_connected, 'total_order_value' => number_format($total_order_value, 2), 'total_admin_commissions' => number_format($total_admin_commissions, 2), 'total_promo_amount'=>$total_promo_amount, 'past_payout_value'=>$past_payout_value, 'available_funds'=>$available_funds, 'payout_options' => $payout_options]);
    }
}
