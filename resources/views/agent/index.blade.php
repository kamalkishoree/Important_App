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
    .table th,.table td, .table td {
        display: table-cell !important;
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
    <div class="row custom-cols">
            <div class="col col-md-4 col-lg-3 col-xl">
                <div class="widget-rounded-circle card">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="text-end">
                                    <p class="text-muted mb-1 text-truncate">{{__("Active") . ' Agents'}}</p>
                                    <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_rejected_order">{{$agentsCount}}</span></h3>
                                </div>
                            </div>
                            <div class="col-4 text-md-right">
                                <div class="avatar-lg rounded-circle ml-auto">
                                    <i class="fe-heart font-22 avatar-title"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-md-4 col-lg-3 col-xl">
                <div class="widget-rounded-circle card">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                        <div class="col-8">
                                <div class="text-end">
                                    <p class="text-muted mb-1 text-truncate">Freelancer</p>
                                    <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_active_order">{{$freelancerCount}}</span></h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="avatar-lg rounded-circle ml-auto">
                                    <i class="fe-shopping-cart font-22 avatar-title"></i>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-md-4 col-lg-3 col-xl">
                <div class="widget-rounded-circle card">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="text-end">
                                    <p class="text-muted mb-1 text-truncate">Employees</p>
                                    <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_delivered_order">{{$employeesCount}}</span></h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="avatar-lg rounded-circle ml-auto">
                                    <i class="fe-bar-chart-line font-22 avatar-title"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-md-4 col-lg-3 col-xl">
                <div class="widget-rounded-circle card">
                    <div class="card-body p-2">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="text-end">
                                    <p class="text-muted mb-1 text-truncate">Active Teams</p>
                                    <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_rejected_order">{{$teamsCount}}</span></h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="avatar-lg rounded-circle ml-auto">
                                    <i class="fe-eye font-22 avatar-title"></i>
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
                        <table class="table table-striped dt-responsive nowrap w-100 all" id="agent-listing">
                            <thead>
                                <tr>
                                    <th>{{__("Uid")}}</th>
                                    <th>{{__("Profile")}}</th>
                                    <th>{{__("Name")}}</th>
                                    <th>{{__("Phone")}}</th>
                                    <th>{{__("Type")}}</th>
                                    <th>{{__("Team")}}</th>
                                    <th>{{__("Vehicle")}}</th>
                                    <th>{{__("Cash Collected")}}</th>
                                    <th>{{__("Order Earning")}}</th>
                                    <th>{{__("Total Paid to Driver")}}</th>
                                    <th>{{__("Total Receive from Driver")}}</th>
                                    <th>{{__("Final Balance")}}</th>
                                    <th>{{__("Is Approved?")}}</th>
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