<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use Omnipay\Omnipay;
use Carbon\Carbon;
use App\Model\{Client, ClientPreference, User, Agent, Order,AgentCashCollectPop,PaymentOption,AgentConnectedAccount};
trait agentDebitThresholdAmount{


    public function AgentDebitThresholdAmount($threshold, $type)
    {

        $stripe_creds   = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr      = isset($stripe_creds->credentials) ? json_decode($stripe_creds->credentials) : null;
        $api_key        = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $this->api_key  = $api_key;
        $primaryCurrency = ClientPreference::with('currency')->select('currency_id')->where('id', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';

        /**automatically debit amount in agent stripe account for day threshold limit*/

        if ($type == 1) {
            \Stripe\Stripe::setApiKey($this->api_key);
            if(($threshold['recursive_type'] == 2 || $threshold['recursive_type'] == 3) && !empty($threshold['threshold_amount'])) {
                //IF driver or agent reached amount before week then payment debit automatcally
               $threshold_amount   = $threshold['threshold_amount'];
               $stripe_connect_id  = $threshold['stripe_connect_id'];
               $orders             = Order::select(DB::raw("SUM(cash_to_be_collected) as cod"), 'driver_id')->where('status', 'completed')->where('status', '!=', null)->orderBy('updated_at', 'desc')->where('cash_to_be_collected', '>', 0)->where('updated_at', '>=', Carbon::today()->toDateString())->groupBy(DB::raw("driver_id"))->get();
               if($orders){
                   foreach ($orders as $order) {
                       $cod                        =  $order->cod;
                       $agent                      =  Agent::where('id', $order->driver_id)->where('is_approved', 1)->with('connectedAccount')->first();
                       $agent_stripe_account_id    =  (isset($agent->connectedAccount->account_id)) ? $agent->connectedAccount->account_id : '';

                        if ($cod >= $threshold_amount) {
                            $currency = $this->currency;
                            $transfer = $this->AgentThresholdAmountByDebitStripe($threshold_amount,$currency,$agent_stripe_account_id,$stripe_connect_id);
                            if(isset($transfer['error'])){
                                $reason             = 'Your balance is not sufficient your stripe connect account';
                                $status             = 2;
                                $transaction_id     = NULL;
                            }else{
                                $transaction_id     = $transfer->balance_transaction;
                                $status             = 1;
                            }
                            $transaction_id                     = $transaction_id;
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = $status;
                            $data                               = ['agent_id' => $agent->id,'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        } else {
                            Agent::where('id', $order->driver_id)->update(['is_threshold' => 0]);
                            $reason                             = 'Your balance is not sufficient your stripe connect account';
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = 2;
                            $data                               = ['agent_id' => $agent->id, 'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        }
                   }
               }
           }

            if ($threshold['recursive_type'] == 1 && !empty($threshold['threshold_amount'])) {
                $threshold_amount    = $threshold['threshold_amount'];
                $stripe_connect_id   = $threshold['stripe_connect_id'];
                $orders              = Order::select(DB::raw("SUM(cash_to_be_collected) as cod"), 'driver_id')->where('status', 'completed')->where('status', '!=', null)->orderBy('updated_at', 'desc')->where('cash_to_be_collected', '>', 0)->where('updated_at', '>=', Carbon::today()->toDateString())->groupBy(DB::raw("driver_id"))->get();

                if ($orders) {
                    $reason = '';
                    foreach ($orders as $order) {
                        $cod                        =  $order->cod;
                        $agent                      =  Agent::where('id', $order->driver_id)->where('is_approved', 1)->with('connectedAccount')->first();
                        $agent_stripe_account_id    =  (isset($agent->connectedAccount->account_id)) ? $agent->connectedAccount->account_id : '';
                        if ($cod >= $threshold_amount) {
                            $currency = $this->currency;
                            $transfer = $this->AgentThresholdAmountByDebitStripe($threshold_amount,$currency,$agent_stripe_account_id,$stripe_connect_id);
                            if(isset($transfer['error'])){
                                $reason             = 'Your balance is not sufficient your stripe connect account';
                                $status             = 2;
                                $transaction_id     = NULL;
                            }else{
                                $transaction_id     = $transfer->balance_transaction;
                                $status             = 1;
                            }
                            $transaction_id                     = $transaction_id;
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = $status;
                            $data                               = ['agent_id' => $agent->id,'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        } else {
                            Agent::where('id', $order->driver_id)->update(['is_threshold' => 0]);
                            $reason                             = 'Your balance is not sufficient your stripe connect account';
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = 2;
                            $data                               = ['agent_id' => $agent->id, 'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        }
                    }
                }
            }
        } else if ($type == 2) {
            /**automatically  agent wallet debit amount for week threshold limit*/
            if ($threshold['recursive_type'] == 2 && !empty($threshold['threshold_amount'])) {
                $threshold_amount   = $threshold['threshold_amount'];
                $stripe_connect_id  = $threshold['stripe_connect_id'];
                $orders             = Order::select(DB::raw("SUM(cash_to_be_collected) as cod"), 'driver_id')->where('status', 'completed')->where('status', '!=', null)->orderBy('updated_at', 'desc')->where('cash_to_be_collected', '>', 0)->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->groupBy(DB::raw("driver_id"))->get();
                if ($orders) {
                    foreach ($orders as $order) {
                        $cod                        =  $order->cod;
                        $agent                      =  Agent::where('id', $order->driver_id)->where('is_approved', 1)->with('connectedAccount')->first();
                        $agent_stripe_account_id    =  (isset($agent->connectedAccount->account_id)) ? $agent->connectedAccount->account_id : '';

                        if ($cod >= $threshold_amount) {
                            $currency = $this->currency;
                            $transfer = $this->AgentThresholdAmountByDebitStripe($threshold_amount,$currency,$agent_stripe_account_id,$stripe_connect_id);
                            if(isset($transfer['error'])){
                                $reason             = 'Your balance is not sufficient your stripe connect account';
                                $status             = 2;
                                $transaction_id     = NULL;
                            }else{
                                $transaction_id     = $transfer->balance_transaction;
                                $status             = 1;
                            }
                            $transaction_id                     = $transaction_id;
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = $status;
                            $data                               = ['agent_id' => $agent->id,'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        } else {
                            Agent::where('id', $order->driver_id)->update(['is_threshold' => 0]);
                            $reason                             = 'Your balance is not sufficient your stripe connect account';
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = 2;
                            $data                               = ['agent_id' => $agent->id, 'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        }
                    }
                }
            }
        } else if ($type == 3) {
            /**automatically  agent wallet debit amount for month threshold limit*/
            if ($threshold['recursive_type'] == 3 && !empty($threshold['threshold_amount'])) {
                $threshold_amount   = $threshold['threshold_amount'];
                $stripe_connect_id  = $threshold['stripe_connect_id'];
                $orders             = Order::select(DB::raw("SUM(cash_to_be_collected) as cod"), 'driver_id')->where('status', 'completed')->where('status', '!=', null)->orderBy('updated_at', 'desc')->where('cash_to_be_collected', '>', 0)->whereMonth('updated_at', Carbon::now()->month)->groupBy(DB::raw("driver_id"))->get();

                if ($orders) {
                    foreach ($orders as $order) {
                        $cod                        =   $order->cod;
                        $agent                      =   Agent::where('id', $order->driver_id)->where('is_approved', 1)->with('connectedAccount')->first();
                        $agent_stripe_account_id    =  (isset($agent->connectedAccount->account_id)) ? $agent->connectedAccount->account_id : '';
                        if ($cod >= $threshold_amount) {
                            $currency = $this->currency;
                            $transfer = $this->AgentThresholdAmountByDebitStripe($threshold_amount,$currency,$agent_stripe_account_id,$stripe_connect_id);
                            if(isset($transfer['error'])){
                                $reason             = 'Your balance is not sufficient your stripe connect account';
                                $status             = 2;
                                $transaction_id     = NULL;
                            }else{
                                $transaction_id     = $transfer->balance_transaction;
                                $status             = 1;
                            }
                            $transaction_id                     = $transaction_id;
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = $status;
                            $data                               = ['agent_id' => $agent->id,'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        } else {
                            Agent::where('id', $order->driver_id)->update(['is_threshold' => 0]);
                            $reason                             = 'Your balance is not sufficient your stripe connect account';
                            $date                               = Carbon::now()->toDateTimeString();
                            $payment_type                       = 1;
                            $threshold_type                     = $type;
                            $status                             = 2;
                            $data                               = ['agent_id' => $agent->id, 'threshold_amount' => $threshold_amount, 'transaction_id' => $transaction_id, 'date' => $date, 'payment_type' => $payment_type, 'threshold_type' => $threshold_type, 'status' => $status,'reason'=>$reason];
                            $this->SaveCollectAmount($data);
                        }
                    }
                }
            }
        }
    }

    /** Agent data store after threshold process */
    public function SaveCollectAmount($data=array()){
        $agentDebitAmount                   = New AgentCashCollectPop();
        $agentDebitAmount->agent_id         = $data['agent_id'];
        $agentDebitAmount->amount           = $data['threshold_amount'];
        $agentDebitAmount->transaction_id   = $data['transaction_id'];
        $agentDebitAmount->date             = $data['date'];
        $agentDebitAmount->payment_type     = $data['payment_type'];
        $agentDebitAmount->threshold_type   = $data['threshold_type'];
        $agentDebitAmount->reason           = $data['reason'];
        $agentDebitAmount->status           = $data['status'];
        $agentDebitAmount->save();
        Agent::where('id',$data['agent_id'])->update(['is_threshold' => 1]);
    }

      /**  Debit amount  */
      public function AgentThresholdAmountByDebitStripe($threshold_amount,$currency,$agent_stripe_account_id,$stripe_connect_id){

        try {

            $payment_intent = \Stripe\PaymentIntent::create([
                'amount' => $threshold_amount * 100,
                'currency' => $this->currency,
            ], ['stripe_account' => $agent_stripe_account_id]);

            // Create a Transfer to a connected account (later):
            $transfer = \Stripe\Transfer::create([
                'amount' => $threshold_amount * 100,
                'currency' => $this->currency,
                'destination' => $stripe_connect_id,
                'transfer_group' => 'admin_payout',
            ]);
           return $transfer;

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return $e->getJsonBody();

        }


      }
    //-------------------------------------------------------------------------------------------//

}
