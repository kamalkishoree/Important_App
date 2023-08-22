<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Agent;
use App\Model\Order;
use App\Model\PaymentOption;
use App\Model\Payment;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveePaymentController extends Controller
{
    use ApiResponser;
    public $currency;
    private $trade_key;
    private $resource_key;
    private $apiUrl;
    // const trade_key = 'sa4b4km6c0l9eq7y6od88cnjp62efvr6ix59u5taz2ghw0193';
    // const TOKEN_API           = "";
    // const resource_key   = "bj65bih1kzo740snwbru2q9px3v5503fetfdaaegmc64yle58";


    public function __construct()
    {
        $payOption = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'livee')->where('status', 1)->first();

        if($payOption->status)
        $credentials = json_decode($payOption->credentials);

        $this->trade_key = $credentials->livee_merchant_key;
        $this->resource_key = $credentials->livee_resource_key;
        $this->apiUrl = "https://www.livees.net/Checkout/api4";

        $this->currency =  'USD';
    }


    public function index(Request $request, $amt)
    {
        try {
            $orderNumber = $this->orderNumber($request);
            $users = $this->createUserToken();
            $user = Auth::user();
            $urlParams = '';
            $amount = $request->amt;
            $nameString = "name";
            $name = strtok(auth()->user()->name, " ");
            $lastname = substr(strstr(auth()->user()->name, " "), 1);
            $email = auth()->user()->email;
            $phone = auth()->user()->phone_number;
            $users = $this->createUserToken();
            if ($request->payment_from == 'cart') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=cart&success=true";
            } elseif ($request->payment_from == 'wallet') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=wallet&success=true";
            } elseif ($request->payment_from == 'subscription') {
                $urlParams   = "transactionid=$orderNumber&subscription_id=$request->subscription_id&amount=$request->amt&success=true";
            } elseif ($request->payment_from == 'pickup_delivery') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=pickup_delivery&reload_route=$request->reload_route&amount=$request->amt&success=true";
            } elseif ($request->payment_from == 'tip') {

                $urlParams   = "transactionid=$orderNumber&order_number=$request->order_number&paymentfrom=tip&amount=$request->amt&success=true";
            }
            $postURL = url('/livee/success' . '?' . $urlParams);

            return view('backend.payment.liveePay', compact('amount', 'postURL', 'user'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function token()
    {
        try {
            $apiUrl = $this->apiUrl;
            $input = json_encode([
                "trade_key" => $this->trade_key,
                "resource_key" => $this->resource_key
            ]);

            $header = [
                'Content-Type' => 'application/json'
            ];
            $response = Http::withBody($input, 'application/json')->withHeaders($header)->post($apiUrl);

            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function orderNumber($request)
    {

        try {
            $time    = isset($request->transaction_id) ? $request->transaction_id : time();
            $user_id = auth()->id();
            $amount  = $request->amt;
           if ($request->payment_from == 'wallet') {
                Payment::create([
                    'amount' => $amount,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'cr' => $amount,
                    'type' => 'wallet',
                    'date' => date('Y-m-d'),
                    'driver_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);
            }
            return $time;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function mobilePay(Request $request, $domain = '')
    {

        $message = '';
        $amount = $request->amount;

        $message = '';
        $amount = $request->amount;
        $user = auth()->user();
        $action = isset($request->action) ? $request->action : '';
        $params = '?amt=' . $amount . '&paymentfrom=' . $action . "&come_from=app&user_id=" . $user->id;
        if ($action == 'cart') {
            $params = $params . '&order_number=' . $request->order_number . '&app=1';
        } elseif ($action == 'wallet') {
            $params = $params . '&app=2&transaction_id=' . time();
        }

        $url = url('payment/livees/api/' . $params);
        \Log::info($url);
        return $this->success(($url));
    }

    public function createUserToken()
    {
        $user = auth()->user();
        $token1 = new Token();
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        $this->token = $token;
        $user->auth_token = $token;
        $user->save();
        return $user;
    }

}
