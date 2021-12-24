@extends('layouts.vertical', ['title' => Session::get('agent_name') ])

@section('css')
<link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<!-- for File Upload -->

<link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('telinput/css/intlTelInput.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
<style>
    .cursors {
        cursor: move;
        margin-right: 0rem !important;
    }

    .table th,
    .table td,
    .table td {
        display: table-cell !important;
    }

    .footer {
        z-index: 3;
    }

    #agent-listing_processing {
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

    div#agent-listing_filter {
        padding-top: 5px;
    }

    .dataTables_filter label {
        width: 25%;
    }

    .dataTables_filter label .form-control {
        height: 37px;
        font-size: 16px;
    }

    .dt-buttons .btn.btn-secondary,
    .dt-buttons .btn.btn-secondary:focus,
    .dt-buttons .btn.btn-secondary:active {
        border-radius: 5px;
        background: #6658ddd6 !important;
    }

    .btn-label,
    .btn-label:focus,
    .btn-label:active {
        background-color: rgb(102 88 221) !important;
    }

    .dataTables_scrollHead thead th {
        cursor: pointer;
    }
    
    .agent-listing tbody td{
        position: relative;
    }

    .nagtive-margin {
        margin-top: -57px;
    }
    .bootstrap-select .dropdown-menu > .inner{
        overflow-y: scroll!important;
    }
    .bootstrap-select .dropdown-menu.inner{
        overflow-y: hidden!important;
    }
</style>
@endsection
@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<div class="container-fluid">
    @csrf
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ Session::get('agent_name') }}</h4>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_earnings_by_vendors">{{$agentsCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total Agents</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fa fa-user-circle text-primary"></i>
                                    <span data-plugin="counterup" id="total_order_count">{{$freelancerCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Freelancer</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fas fa-user text-primary"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$employeesCount}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Employees</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fa fa-address-card text-primary"></i>
                                    <span data-plugin="counterup" id="total_delivery_fees">{{$agentIsApproved}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Approved Agents</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="fa fa-user-times text-primary"></i>
                                    <span data-plugin="counterup" id="total_delivery_fees">{{$agentNotApproved}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Unapproved Agents</p>
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
                    <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="active-vendor" data-toggle="tab" href="#active_vendor" role="tab" aria-selected="false" data-rel="agent-listing" data-status="1">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __('Active') }}<sup class="total-items" id="active_vendor_count">({{$agentIsApproved}})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="awaiting-vendor" data-toggle="tab" href="#awaiting_vendor" role="tab" aria-selected="true" data-rel="awaiting_approval_agent_datatable" data-status="0">
                                <i class="icofont icofont-ui-home"></i>{{ __('Awaiting Approval') }}<sup class="total-items">({{$agentNotApproved}})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="block-vendor" data-toggle="tab" href="#block_vendor" role="tab" aria-selected="false" data-rel="blocked_agent_datatable" data-status="2">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __('Blocked') }}<sup class="total-items">({{$agentRejected}})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                    </ul>
                    <div class="row mt-3">
                        <div class="col-sm-2">
                            <label for="geo_filter">{{__('Location Filter')}}</label>
                            <select name="geo_filter" id="geo_filter" class="form-control">
                                <option value="">{{__('All')}}</option>
                                @foreach($geos as $geo)
                                    <option value="{{$geo->id}}">{{$geo->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tab-content nav-material pt-0" id="top-tabContent">
                        <div class="tab-pane fade past-order show active" id="active_vendor" role="tabpanel" aria-labelledby="active-vendor">
                            <div class="row mb-2 mt-3">
                                <div class="col-sm-12">
                                    <div class="text-sm-left">
                                        @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4 btn-auto">
                                    <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add")}} {{ Session::get('agent_name') }}</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light saveaccounting" data-toggle="modal" data-target="#pay-receive-modal" data-backdrop="static" data-keyboard="false">{{__("Pay")}} / {{__("Receive")}}</button>
                                </div>
                            </div>

                            <div class="table-responsive nagtive-margin">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="agent-listing">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__("Agent ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("UID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("Vehicle")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Paid to Agent")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Receive from Agent")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Requested At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Approved At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th>{{__("Action")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="awaiting_vendor" role="tabpanel" aria-labelledby="awaiting-vendor">
                            <div class="row">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="awaiting_approval_agent_datatable">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__("Agent ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("UID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("Vehicle")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Paid to Agent")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Receive from Agent")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Reuested At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Approved At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th>{{__("Action")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade past-order" id="block_vendor" role="tabpanel" aria-labelledby="block-vendor">
                            <div class="row">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="blocked_agent_datatable">
                                    <thead>
                                        <tr>
                                            <th class="sort-icon">{{__("Agent ID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("UID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            {{-- <th class="sort-icon">{{__("Vehicle")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th> --}}
                                            <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Paid to Agent")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Total Receive from Agent")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Requested At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th class="sort-icon">{{__("Rejected At")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                            <th>{{__("Action")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
    </div>
</div>

@include('agent.modals')
@include('modals.pay-receive')
@endsection

@section('script')

<!-- <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}"> -->
<script src="{{ asset('assets/js/storeAgent.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />


<script>
    $('#selectAgent').on('change', function(e) {

        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        $.ajax({
            type: 'get',
            url: "{{ url('/agent/paydetails') }}/" + valueSelected,
            data: '',
            success: function(data) {
                var order = round(data.order_cost, 2);
                var driver_cost = round(data.driver_cost, 2);
                var credit = round(data.credit, 2);
                var debit = round(data.debit, 2);
                var cash = round(data.cash_to_be_collected, 2);
                var final = round(cash - driver_cost, 2);
                var wallet_balance = round(data.wallet_balance, 2);
                var new_final = round(wallet_balance + (debit - credit) - (cash - driver_cost), 2);
                $("#order_earning").text(driver_cost);
                $("#cash_collected").text(cash);
                $("#final_balance").text(new_final);
            },
        });

    });

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }

    $("#submitpayreceive").submit(function(stay) {
        var formdata = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('pay.receive') }}",
            data: formdata,
            success: function(response) {
                if (response.status == 'Success') {
                    $("#pay-receive-modal .close").click();
                    location.reload();
                } else {
                    $("#pay-receive-modal .show_all_error.invalid-feedback").show();
                    $("#pay-receive-modal .show_all_error.invalid-feedback").text(response.message);
                }
            },
            error: function(response){
                let errors = response.responseJSON;
                $("#pay-receive-modal .show_all_error.invalid-feedback").show();
                $("#pay-receive-modal .show_all_error.invalid-feedback").text(errors.message);
            }
        });
        stay.preventDefault();
    });
</script>
@include('agent.pagescript')
@endsection