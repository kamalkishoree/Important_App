<?php

namespace App\Http\Controllers\Api;
use DB;
use Auth;
use Session;
use Validator;
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
use App\Model\{Client, ClientPreference, User, Agent, Order, PaymentOption, PayoutOption, AgentPayout, AgentBankDetail};

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
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|gt:0',
                'payout_option_id' => 'required'
            ],[
                'payout_option_id.required' => 'Payout option field is required'
            ]);
    
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 422);
            }

            DB::beginTransaction();
            $agent = Agent::where('id',$id);
            // $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
            $user = Auth::user();
            $agent = $agent->first();
            $agent_id = $agent->id;
            $credit = $agent->agentPayment->sum('cr');
            $debit = $agent->agentPayment->sum('dr');

            $total_order_value = Order::where('driver_id', $agent_id)->orderBy('id','desc');
            $total_order_value = $total_order_value->sum('order_cost');

            $agent_payouts = AgentPayout::where('agent_id', $agent_id)->orderBy('id','desc');
            $agent_payouts = $agent_payouts->whereIn('status', [0,1])->sum('amount');

            $past_payout_value = $agent_payouts;
            $available_funds = $total_order_value + $agent->balanceFloat + $debit - $past_payout_value - $credit;

            if($request->amount > $available_funds){
                return $this->error(__('Payout amount is greater than available funds'), 402);
            }

            $preferences = ClientPreference::select('currency_id')->where('id', 1)->first();

            $pay_option = $request->payout_option_id ?? 1;
            if($pay_option == 4){

                $validator = Validator::make($request->all(), [
                    'beneficiary_name' => 'required',
                    'beneficiary_address' => 'required',
                    'beneficiary_account_number' => 'required',
                    'beneficiary_ifsc' => 'required'
                ]);
        
                if ($validator->fails()) {
                    return $this->error($validator->errors()->first(), 422);
                }

                $beneficiary_name = $request->beneficiary_name;
                $beneficiary_address = $request->beneficiary_address;
                $bank_account = $request->beneficiary_account_number;
                $bank_ifsc = $request->beneficiary_ifsc;
                $agent_bank_account = AgentBankDetail::where('beneficiary_account_number', $bank_account)->where('beneficiary_ifsc', $bank_ifsc)->where('status', 1)->orderBy('id', 'desc')->first();
                if($agent_bank_account){
                    $agent_bank_account->beneficiary_name = $beneficiary_name;
                    $agent_bank_account->beneficiary_account_number = $bank_account;
                    $agent_bank_account->beneficiary_ifsc = $bank_ifsc;
                    $agent_bank_account->beneficiary_address = $beneficiary_address;
                    $agent_bank_account->status = 1;
                    $agent_bank_account->update();
                }
                else{
                    // find any other account of current agent and inactive that account
                    $get_agent_existing_account = AgentBankDetail::where('agent_id', $agent_id)->where('status', 1)->orderBy('id', 'desc')->first();
                    if($get_agent_existing_account){
                        $get_agent_existing_account->status = 0;
                        $get_agent_existing_account->update();
                    }

                    $agent_bank_account = new AgentBankDetail();
                    $agent_bank_account->agent_id = $agent_id;
                    $agent_bank_account->payout_option_id = $pay_option;
                    $agent_bank_account->beneficiary_name = $beneficiary_name;
                    $agent_bank_account->beneficiary_account_number = $bank_account;
                    $agent_bank_account->beneficiary_ifsc = $bank_ifsc;
                    $agent_bank_account->beneficiary_address = $beneficiary_address;
                    $agent_bank_account->status = 1;
                    $agent_bank_account->save();
                }
            }

            $payout = new AgentPayout();
            $payout->agent_id = $id;
            $payout->payout_option_id = $pay_option;
            $payout->transaction_id = ($pay_option != 1) ? $request->transaction_id : '';
            $payout->amount = $request->amount;
            $payout->currency = $preferences->currency_id;
            $payout->requested_by = $agent->id;
            $payout->status = 0;
            if($pay_option == 4){
                $payout->agent_bank_detail_id = $agent_bank_account->id;
            }
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
    public function agentPayoutDetails(Request $request)
    {
        $page = $request->has('page') ? $request->page : 1;
        $limit = $request->has('limit') ? $request->limit : 30;
        $user = Auth::user();
        $agent = Agent::where('id',$user->id)->first();
        $credit = $agent->agentPayment->sum('cr');
        $debit = $agent->agentPayment->sum('dr');
        $agent_id = $agent->id;

        $client_preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();

        $total_order_value = Order::where('driver_id', $agent_id)->orderBy('id','desc');
        $total_order_value = $total_order_value->sum('order_cost');

        $agent_payouts = AgentPayout::select('*','status as status_id')->with('payoutOption')->where('agent_id', $agent_id)->orderBy('id','desc');
        $past_payout_value = $agent_payouts->whereIn('status', [0,1])->sum('amount');
        $agent_payout_list = $agent_payouts->paginate($limit, $page);

        $available_funds = $total_order_value + $agent->balanceFloat + $debit - $past_payout_value - $credit;
        // $available_funds = number_format($available_funds, 2, '.', ',');
        $past_payout_value = number_format($past_payout_value, 2, '.', ',');


        // Payout Options start
        $code = array('cash', 'razorpay', 'bank_account_m_india');
        $payout_options = PayoutOption::whereIn('code', $code)->where('status', 1)->get(['id', 'code', 'title', 'credentials', 'off_site']);
        foreach($payout_options as $option){
            $creds_arr = json_decode($option->credentials);
            if($option->code == 'stripe'){
                $option->title = 'Credit/Debit Card (Stripe)';
            }
            elseif($option->code == 'razorpay'){
                $option->api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            }
            unset($option->credentials);
            $option->title = __($option->title);
        }
        // Payout Options end

        $data = array(
            'agent' => $agent, 
            'payout_options' => $payout_options,
            'client_preferences' => $client_preferences,
            'agent_payout_list' => $agent_payout_list,
            'past_payout_value' => $past_payout_value, 
            'available_funds' => $available_funds
        );

        return $this->success($data, __('Success'), 201);
    }


    /**   get agent payout bank details  */
    public function agentBankDetails(Request $request)
    {
        $user = Auth::user();
        $agent = Agent::where('id',$user->id)->first();
        $agent_id = $agent->id;

        $client_preferences = ClientPreference::with('currency')->where('id', '>', 0)->first();
        $agent_bank_details = AgentBankDetail::with(['payoutOption'])->where('agent_id', $agent_id)->where('status', 1)->orderBy('id','desc')->first();
        
        $data = array(
            'agent' => $agent, 
            'client_preferences' => $client_preferences,
            'agent_bank_details' => $agent_bank_details
        );

        return $this->success($data, __('Success'), 201);
    }
}
