@extends('layouts.vertical', ['title' => 'Agents threshold'])

@section('css')
<style>
    div#frm-error {
        padding-left: 10px;
    }
    div#frm-error p {
        color: red;
        font-size: 15px;
    }
    .table th,.table td, .table td {
        display: table-cell !important;
    }

    input.form-control.form-control-sm {
        display: none;
    }

    .footer{
        z-index: 3;
    }
    #pricing-datatable_processing {
        position: absolute !important;
        background: transparent !important;
        top: 60%;
        transform: translateY(-50%) !important;
        left: 0;
        right: 0;
        z-index: 1;
    }
    .dt-buttons.btn-group.flex-wrap {
        float: right;
        margin: 5px 0 10px 15px;
    }
    div#pricing-datatable_filter {
        padding-top: 5px;
    }
    .dataTables_filter label {
        width: 25%;
    }
    .dataTables_filter label .form-control {
        height: 37px;
        font-size: 16px;
    }
    .dt-buttons .btn.btn-secondary,.dt-buttons .btn.btn-secondary:focus,.dt-buttons .btn.btn-secondary:active {
        border-radius: 5px;
        background: #6658ddd6 !important;
    }
    .btn-label,.btn-label:focus,.btn-label:active {
        background-color: rgb(102 88 221) !important;
    }
    .dataTables_scrollHead thead th {
        cursor: pointer;
    }

    .nagtive-margin {
        margin-top: -57px;
    }
</style>
@endsection
@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title page-title1">{{ __("Threshold Agent List") }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card widget-inline main-card-header">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_earnings_by_vendors">{{$agentsCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{ __('Total Agents') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_order_count">{{$AutomatcallyPayments}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Automatcally Payments')}}</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-money-check-alt text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$ManuallyPayments}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Manually Payments')}}</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-money-check-alt text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$ApprovedPayments}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Approved Payments')}}</p>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-money-check-alt text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$PendingPayments}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Pending Payments')}}</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-money-check-alt text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$RejectedPayments}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{__('Rejected Payments')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive nagtive-margin">
                        <table class="table table-striped dt-responsive nowrap w-100"  id="pricing-datatable">
                            <thead>
                                <tr>
                                    <th>{{__("Agent Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Amount")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Transaction ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("date")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Payment Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Threshold Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Status")}}</th>
                                    <th>{{__("Action")}}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

</div>

<div class="modal"  id="payment_status" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Threshold Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-submit" disabled>Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
<script src="{{asset('assets/js/storeAgent.js')}}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
@include('agent_threshold.pagescript')
<script>
    $( document ).delegate( "#payment_action", "change", function() {
       var id       = $(this).val();

       if (id == 2){
            $("#rejected").empty();
            $("#rejected").html("<td>Reason</td><td><textarea class='form-control' name='admin_reason' id='admin_reason' placeholder='Enter reason' rows='5'></textarea></td>");
            $("#payment_status").find('.btn-submit').prop('disabled',false);

       }else  if (id == 1){
            $("#rejected").empty();
            $("#payment_status").find('.btn-submit').prop('disabled',false);
       }else{
            $("#payment_status").find('.btn-submit').prop('disabled',true);
       }
    });
</script>
@endsection
