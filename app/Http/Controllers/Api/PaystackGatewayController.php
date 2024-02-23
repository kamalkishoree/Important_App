<?php
namespace App\Http\Controllers\Api;
use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Model\{PaymentOption, Cart, Client, ClientPreference, ClientCurrency, SubscriptionPlansUser};
class PaystackGatewayController extends BaseController
{
    use ApiResponser;
    public $gateway;
    public $currency;
    public $currency_id;
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
    public function paystackPurchase(Request $request){
        try{
            $rules = [
                'amount'   => 'required',
                'action'   => 'required'
            ];
            $user = Auth::user();
            $amount = $request->amount;
            $request->request->add(['payment_form' => $request->action]);
            $meta_data = array();
            $reference_number = $description = $returnUrlParams = '';
            $returnUrl = $request->serverUrl . 'payment/paystack/completePurchase/app?amount='.$amount.'&status=200&gateway=paystack&action='.$request->action;
            $cancelUrl = $request->serverUrl . 'payment/paystack/cancelPurchase/app?status=0&gateway=paystack&action='.$request->action;
          if($request->payment_form == 'wallet'){
                $description = 'Wallet Checkout';
                $returnUrlParams = $returnUrlParams.'&user_id='.$user->id;
                $meta_data['custom_fields']['user_id'] = $user->id;
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->error(__($validator->errors()->first()), 422);
            }
            $meta_data['cancel_action'] = url($cancelUrl . $returnUrlParams);
            $response = $this->gateway->purchase([
                'amount' => $amount,
                'currency' => $this->currency,
                'email' => strtolower(str_replace(' ', '', $user->name ?? ''))."".(str_replace(' ', '', $user->phone_number ?? '')) . '@gmail.com',
                'returnUrl' => url($returnUrl . $returnUrlParams),
                'cancelUrl' => url($cancelUrl . $returnUrlParams),
                'metadata' => $meta_data,
                'description' => $description,
            ])->send();
            if ($response->isSuccessful()) {
                return $this->success($response->getData());
            }
            elseif ($response->isRedirect()) {
                return $this->success($response->getRedirectUrl());
            }
            else {
                return $this->error($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            return $this->error($ex->getMessage(), 400);
        }
    }
}