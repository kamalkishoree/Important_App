<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use Session;
use DataTables;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\ToasterResponser;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderVendorListExport;
use App\Http\Controllers\BaseController;
use App\Model\{User, Vendor, OrderVendor, PaymentOption, PayoutOption, VendorConnectedAccount, VendorPayout, ClientCurrency};

class VendorPayoutController extends BaseController{
    use ApiResponser;
    use ToasterResponser;
    public $gateway;
    public $currency;

    public function __construct(){
        $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        if($stripe_creds){
            $creds_arr = json_decode($stripe_creds->credentials);
            $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;
            $this->gateway = Omnipay::create('Stripe');
            $this->gateway->setApiKey($api_key);
            $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        }

    }

    public function index(Request $request){
        $total_delivery_fees = OrderVendor::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_delivery_fees = $total_delivery_fees->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_delivery_fees = $total_delivery_fees->sum('delivery_fee');

        $total_admin_commissions = OrderVendor::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_admin_commissions = $total_admin_commissions->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_admin_commissions = $total_admin_commissions->sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));

        $total_order_value = OrderVendor::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_order_value = $total_order_value->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_order_value = $total_order_value->sum('payable_amount') - $total_delivery_fees;

        return view('backend.payment.vendor-payout')->with(['total_order_value' => number_format($total_order_value, 2), 'total_admin_commissions' => number_format($total_admin_commissions, 2)]);
    }

    public function filter(Request $request){
        $from_date = "";
        $to_date = "";
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
        }
        $vendors = Vendor::with(['orders' => function($query) use($from_date,$to_date) {
            if((!empty($from_date)) && (!empty($to_date))){
                $query->between($from_date." 00:00:00", $to_date." 23:59:59");
            }
        }])->where('status', '!=', '2')->orderBy('id', 'desc');

        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }

        $vendors = $vendors->get();
        foreach ($vendors as $vendor) {
            $vendor->total_paid = 0.00;
            // $vendor->url = route('vendor.show', $vendor->id);
            $vendor->view_url = route('vendor.show', $vendor->id);
            $vendor->delivery_fee = number_format($vendor->orders->sum('delivery_fee'), 2, ".","");
            $vendor->payable_amount = number_format($vendor->orders->sum('payable_amount'),2, ".","");
            $vendor->order_value = number_format(($vendor->payable_amount - $vendor->delivery_fee), 2, ".","");
            // $vendor->payment_method = number_format($vendor->orders->whereIn('payment_option_id', [2,3, 4])->sum('payable_amount'), 2, ".","");
            // $vendor->promo_admin_amount = number_format($vendor->orders->where('coupon_paid_by', 1)->sum('discount_amount'), 2, ".","");
            // $vendor->promo_vendor_amount = number_format($vendor->orders->where('coupon_paid_by', 0)->sum('discount_amount'), 2, ".","");
            // $vendor->cash_collected_amount = number_format($vendor->orders->where('payment_option_id', 1)->sum('payable_amount'), 2, ".","");
            $vendor->admin_commission_amount = number_format($vendor->orders->sum('admin_commission_percentage_amount') + $vendor->orders->sum('admin_commission_fixed_amount'), 2, ".","");
            // $vendor->vendor_earning = number_format(($vendor->orders->sum('payable_amount') - $vendor->promo_vendor_amount - $vendor->promo_admin_amount - $admin_commission_amount), 2, ".","");

            $is_stripe_connected = 0;
            $checkIfStripeAccountExists = VendorConnectedAccount::where('vendor_id', $vendor->id)->first();
            if($checkIfStripeAccountExists && (!empty($checkIfStripeAccountExists->account_id))){
                $is_stripe_connected = 1;
            }
            $vendor->is_stripe_connected = $is_stripe_connected;

            $vendor->vendor_earning = number_format(($vendor->order_value - $vendor->admin_commission_amount), 2, ".","");
        }
        return Datatables::of($vendors)
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

    public function export() {
        return Excel::download(new OrderVendorListExport, 'vendor_list.xlsx');
    }


    public function vendorPayoutRequests(Request $request){

        $total_delivery_fees = OrderVendor::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_delivery_fees = $total_delivery_fees->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_delivery_fees = $total_delivery_fees->sum('delivery_fee');

        $total_admin_commissions = OrderVendor::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_admin_commissions = $total_admin_commissions->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_admin_commissions = $total_admin_commissions->sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));

        $total_order_value = OrderVendor::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_order_value = $total_order_value->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_order_value = $total_order_value->sum('payable_amount') - $total_delivery_fees;

        $pending_payouts = VendorPayout::where('status', 0);
        $completed_payouts = VendorPayout::whereIn('status', [1,2]);
        $pending_payout_value = $pending_payouts->sum('amount');
        $completed_payout_value = $completed_payouts->sum('amount');
        $pending_payout_count = $pending_payouts->count();
        $completed_payout_count = $completed_payouts->count();
        $payout_options = PayoutOption::where('status', 1)->get();
        $client_currency = ClientCurrency::with('currency')->where('is_primary', 1)->first();
        $currency_symbol = $client_currency->currency->symbol ?? '$';

        return view('backend.payment.vendorPayoutRequests')->with(['total_order_value' => number_format($total_order_value, 2), 'total_admin_commissions' => number_format($total_admin_commissions, 2), 'pending_payout_value'=>$pending_payout_value, 'completed_payout_value'=>$completed_payout_value, 'pending_payout_count'=>$pending_payout_count, 'completed_payout_count'=>$completed_payout_count, 'payout_options'=>$payout_options, 'currency_symbol'=>$currency_symbol]);
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
        $vendor_payouts = VendorPayout::with(['vendor', 'user', 'payoutOption'])->orderBy('id','desc');
        if($user->is_superadmin == 0){
            $vendor_payouts = $vendor_payouts->whereHas('vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $vendor_payouts = $vendor_payouts->where('status', $status)->get();
        foreach ($vendor_payouts as $payout) {
            $payout->date = dateTimeInUserTimeZone($payout->created_at, $user->timezone);
            $payout->vendorName = $payout->vendor->name;
            $payout->requestedBy = ucfirst($payout->user->name);
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
            $payout = VendorPayout::where('id', $id)->first();
            $user = Auth::user();
            $vendor_id = $payout->vendor_id;

            $total_delivery_fees = OrderVendor::where('vendor_id', $vendor_id)->orderBy('id','desc');
            if ($user->is_superadmin == 0) {
                $total_delivery_fees = $total_delivery_fees->whereHas('vendor.permissionToUser', function ($query) use($user) {
                    $query->where('user_id', $user->id);
                });
            }
            $total_delivery_fees = $total_delivery_fees->sum('delivery_fee');

            $total_promo_amount = OrderVendor::where('vendor_id', $vendor_id)->orderBy('id','desc');
            if ($user->is_superadmin == 0) {
                $total_promo_amount = $total_promo_amount->whereHas('vendor.permissionToUser', function ($query) use($user) {
                    $query->where('user_id', $user->id);
                });
            }
            $total_promo_amount = $total_promo_amount->where('coupon_paid_by', 0)->sum('discount_amount');

            $total_admin_commissions = OrderVendor::where('vendor_id', $vendor_id)->orderBy('id','desc');
            if ($user->is_superadmin == 0) {
                $total_admin_commissions = $total_admin_commissions->whereHas('vendor.permissionToUser', function ($query) use($user) {
                    $query->where('user_id', $user->id);
                });
            }
            $total_admin_commissions = $total_admin_commissions->sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));

            $total_order_value = OrderVendor::where('vendor_id', $vendor_id)->orderBy('id','desc');
            if ($user->is_superadmin == 0) {
                $total_order_value = $total_order_value->whereHas('vendor.permissionToUser', function ($query) use($user) {
                    $query->where('user_id', $user->id);
                });
            }
            $total_order_value = $total_order_value->sum('payable_amount') - $total_delivery_fees;

            $vendor_payouts = VendorPayout::where('vendor_id', $vendor_id)->orderBy('id','desc');
            if($user->is_superadmin == 0){
                $vendor_payouts = $vendor_payouts->whereHas('vendor.permissionToUser', function ($query) use($user) {
                    $query->where('user_id', $user->id);
                });
            }
            $vendor_payouts = $vendor_payouts->where('status', 1)->sum('amount');

            $past_payout_value = $vendor_payouts;
            $available_funds = $total_order_value - $total_admin_commissions - $total_promo_amount - $past_payout_value;

            if($request->amount > $available_funds){
                $toaster = $this->errorToaster('Error', __('Payout amount is greater than vendor available funds'));
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
