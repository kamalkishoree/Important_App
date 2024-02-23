<?php
namespace App\Http\Controllers;
use App\Model\AgentPayment;
use App\Model\ClientPreference;
use App\Model\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Omnipay\Omnipay;
use App\Traits\ApiResponser;
class PaystackGatewayController extends Controller
{
    
    public $gateway;
    public $currency;
    public $currency_id;
    use ApiResponser;
    public function __construct()
    {
        $paystack_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paystack')->where('status', 1)->first();
        $creds_arr = json_decode($paystack_creds->credentials);
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        $testmode = (isset($paystack_creds->test_mode) && ($paystack_creds->test_mode == '1')) ? true : false;
        
        $this->gateway = Omnipay::create('Paystack');
        $this->gateway->setSecretKey($secret_key);
        $this->gateway->setPublicKey($public_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        $primaryCurrency = ClientPreference::with('currency')->select('currency_id')
        ->where('id', 1)
        ->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
        $this->currency_id = (isset($primaryCurrency->currency_id)) ? $primaryCurrency->currency_id : '';
    }
    public function paystackCancelPurchaseApp(Request $request)
    {
        $url = 'payment/gateway/returnResponse?status=0&gateway=paystack&action='.$request->action;
        return Redirect::to($url);
    }
    public function paystackCompletePurchaseApp(Request $request)
    {
       // pr($request-);
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['reference'])){
            $amount = $request->amount;
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'transactionReference'  => $request->reference
            ));
            $response = $transaction->send();
            $payment_form = $request->action;
            if($payment_form == 'wallet'){
                $request->request->add(['wallet_amount' => $amount, 'transaction_id' => $request->reference]);
                $walletController = new WalletController();
                $walletController->creditAgentWallet($request);
                $data = [
                    'driver_id' => $request->user_id,
                    'dr' =>  $amount ?? 0,
                    'payment_from' => $request->payment_from == 2 ? 1:0
                ];
                $agent = AgentPayment::create($data);
            }
              
            if ($response->isSuccessful()){
                return $this->success($response->getTransactionReference());
            } else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        } else {
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }
}
