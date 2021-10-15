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
                    <div class="row mb-2">

                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right btn-auto">
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add")}} {{ Session::get('agent_name') }}</button>
                            <button type="button" class="btn btn-success waves-effect waves-light saveaccounting" data-toggle="modal" data-target="#pay-receive-modal" data-backdrop="static" data-keyboard="false">{{__("Pay")}} / {{__("Receive")}}</button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100 all agent-listing" id="agent-listing">
                            <thead>
                                <tr>
                                    <th class="sort-icon">{{__("UID")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Profile")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>

                                    <th class="sort-icon">{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>

                                    <th class="sort-icon">{{__("Phone")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Type")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Team")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Vehicle")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Cash Collected")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Order Earning")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Total Paid to Driver")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Total Receive from Driver")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th class="sort-icon">{{__("Final Balance")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__("Is Approved?")}}</th>
                                    <th>{{__("Is Active?")}}</th>
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
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />>


<script>
    $('#selectAgent').on('change', function(e) {

        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        $.ajax({
            type: 'get',
            url: "{{ url('/agent/paydetails') }}/" + valueSelected,
            data: '',
            success: function(data) {
                console.log(data);
                var order = round(data.order_cost, 2);
                var driver_cost = round(data.driver_cost, 2);
                var credit = round(data.credit, 2);
                var debit = round(data.debit, 2);
                var cash = round(data.cash_to_be_collected, 2);
                var final = round(cash - driver_cost, 2);
                var new_final = round((debit - credit) - (cash - driver_cost), 2);
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
            success: function(data) {
                $("#pay-receive-modal .close").click();
                location.reload();
            },
        });
        stay.preventDefault();
    });
</script>
@include('agent.pagescript')
@endsection