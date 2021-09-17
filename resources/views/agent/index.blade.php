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
    .table th:last-child,.table td:nth-last-child(2), .table td:last-child {
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
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4 text-left btn-auto d-flex align-items justify-content">
                            <div class="form-group mb-0 mr-2">
                                <input class="form-control" placeholder="Select date" id="sort-date-agent" name="sort_date_agent" value="{{!empty($calenderSelectedDate) ? $calenderSelectedDate : ''}}" type="text" autocomplete="off">
                            </div>
                            <!-- <a href="javascript:void(0);" class="btn btn-blue" id="sort-agent">Go</a> -->
                            <a href="javascript:void(0);" class="btn btn-success ml-2" id="sort-agent-clear">Clear</a>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right btn-auto">
                            <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add {{ Session::get('agent_name') }}</button>
                            <button type="button" class="btn btn-success waves-effect waves-light saveaccounting" data-toggle="modal" data-target="#pay-receive-modal" data-backdrop="static" data-keyboard="false">Pay / Receive</button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100 all" id="agent-listing">
                            <thead>
                                <tr>
                                    <th>Uid</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Type</th>
                                    <th>Team</th>
                                    <th>Vehicle</th>
                                    <th>Cash Collected</th>
                                    <th>Order Earning</th>
                                    <th>Total Paid to Driver</th>
                                    <th>Total Receive from Driver</th>
                                    <th>Final Balance</th>
                                    <th>Is Approved?</th>
                                    <th>Action</th>
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

<script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<script src="{{ asset('assets/js/storeAgent.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />>

@include('agent.pagescript')
<script>

    initDataTable();
    function initDataTable() {
        $('#agent-listing').DataTable({
            "dom": '<"toolbar">Bfrtip',
            "scrollX": true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "iDisplayLength": 50,
            language: {
                        search: "",
                        paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                        searchPlaceholder: "Search Agent"
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            buttons: [{   
                className:'btn btn-success waves-effect waves-light',
                text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>Export CSV',
                action: function ( e, dt, node, config ) {
                    window.location.href = "{{ route('agents.export') }}";
                }
            }],
            ajax: {
                url: "{{url('agent/filter')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.date_filter = $('#sort-date-agent').val();
                    d.imgproxyurl = '{{$imgproxyurl}}';
                }
            },
            columns: [
                {data: 'uid', name: 'uid', orderable: true, searchable: false},
                {data: 'profile_picture', name: 'profile_picture', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                    return '<img alt="'+full.id+'" src="'+full.profile_picture+'" width="40">';
                }},
                {data: 'name', name: 'name', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                    return '<a href="javascript:void(0);" class="text-body font-weight-semibold">'+full.name+'</a>';
                }},
                {data: 'phone_number', name: 'phone_number', orderable: true, searchable: false},
                {data: 'type', name: 'type', orderable: true, searchable: false},
                {data: 'team', name: 'team', orderable: true, searchable: false},
                {data: 'vehicle_type_id', name: 'vehicle_type_id', orderable: true, searchable: false, "mRender": function ( data, type, full ) {
                    return '<img alt="" style="width: 80px;" src="'+full.vehicle_type_id+'">';
                }},
                {data: 'cash_to_be_collected', name: 'cash_to_be_collected', orderable: false, searchable: false},
                {data: 'driver_cost', name: 'driver_cost', orderable: false, searchable: false},
                {data: 'cr', name: 'cr', orderable: false, searchable: false},
                {data: 'dr', name: 'dr', orderable: false, searchable: false},
                {data: 'pay_to_driver', name: 'pay_to_driver', orderable: false, searchable: false},
                {data: 'is_approved', name: 'is_approved', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                    var check = (full.is_approved == 1)? 'checked' : '';
                    return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input agent_approval_switch" '+check+' id="customSwitch_'+full.id+'" data-id="'+full.id+'"><label class="custom-control-label" for="customSwitch_'+full.id+'"></label></div>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                
            ]
        });         
              
    }


    $("#sort-date-agent").flatpickr({ 
        mode: "range",
        onClose: function(selectedDates, dateStr, instance) {
            initDataTable();
        }
    });

    $('#sort-agent-clear').on('click', function(e) {
        $('#sort-date-agent').val('');
        initDataTable();
    });
    // $('#sort-agent').on('click', function(e) {
    //     var sortDateAgent = $('#sort-date-agent').val();
    //     if (sortDateAgent != '') {
    //         var perm = "?date=" + sortDateAgent;
    //         window.history.replaceState(null, null, perm);
    //         location.reload();
    //     }
    // });

    // $('#selectAgent').on('change', function(e) {

    //     var optionSelected = $("option:selected", this);
    //     var valueSelected = this.value;
    //     $.ajax({
    //         type: 'get',
    //         url: "{{ url('/agent/paydetails') }}/" + valueSelected,
    //         data: '',
    //         success: function(data) {
    //             console.log(data);
    //             var order = round(data.order_cost, 2);
    //             var driver_cost = round(data.driver_cost, 2);
    //             var credit = round(data.credit, 2);
    //             var debit = round(data.debit, 2);
    //             var cash = round(data.cash_to_be_collected, 2);
    //             var final = round(cash - driver_cost, 2);
    //             var new_final = round((debit - credit) - (cash - driver_cost), 2);
    //             $("#order_earning").text(driver_cost);
    //             $("#cash_collected").text(cash);
    //             $("#final_balance").text(new_final);
    //         },
    //     });

    // });

    // function round(value, exp) {
    //     if (typeof exp === 'undefined' || +exp === 0)
    //         return Math.round(value);

    //     value = +value;
    //     exp = +exp;

    //     if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    //         return NaN;

    //     // Shift
    //     value = value.toString().split('e');
    //     value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

    //     // Shift back
    //     value = value.toString().split('e');
    //     return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    // }

    // $("#submitpayreceive").submit(function(stay) {

    //     var formdata = $(this).serialize();
    //     $.ajax({
    //         type: 'POST',
    //         url: "{{ route('pay.receive') }}",
    //         data: formdata,
    //         success: function(data) {
    //             $("#pay-receive-modal .close").click();
    //             location.reload();
    //         },
    //     });
    //     stay.preventDefault();
    // });
</script>
@endsection