@extends('layouts.vertical', ['title' => 'Profile'])

@section('css')
<link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .submit-btn:hover {
    background-color: #3283f6 !important;
}
.main_form .select2-container--default .select2-selection--single {
    height: 38px;
}
.main_form .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
}
.main_form .select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-top: 4px !important;
}
.main_form .select2-container--default .select2-selection--single {
    border: 1px solid #ced4da !important;
}
.main_form .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #44444487 !important;
}
.dt-buttons.btn-group.flex-wrap {
    display: none;
}
</style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{__("Driver")}}</h4>
            </div>
        </div>
        <div class="col-md-12 main_form">
            {{-- Filter form --}}
            <div class="row">
                <div class="col-md-3">
                    <form action="{{ route('driver-accountancy.index') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">From Date</label>
                                <input type="text" name="date_picker" id="date_picker" class="form-control" placeholder="Select Date Range">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-5">
                    <form class="mb-0" name="getTask" id="getTask" method="get" action="{{ route('driver-accountancy.index') }}">
                        <div class="login-form">
                            <ul class="list-inline mb-0">
                                <li class="d-inline-block mr-2 {{ $status == 'settlement' ? 'border-0' : '' }}">
                                    <input type="radio" id="teacher" name="status" onclick="handleClick(this);"
                                        value="settlement" {{ $status == 'settlement' ? 'checked' : '' }}>
                                    <label for="teacher">{{__("Settlement")}}</label>
                                </li>
                                <li class="d-inline-block mr-2 {{ $status == 'statement' ? 'border-0' : '' }}">
                                    <input type="radio" id="student" onclick="handleClick(this);" name="status"
                                        value="statement" {{ $status == 'statement' ? 'checked' : '' }}>
                                    <label for="student">{{__("Statement")}}</label>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
            

            
            {{-- Filter form end --}}

            <div class="text-center">
                <h2>Total Commision</h2>
                <h3>{{ $order_sum ?? 0 }}</h3>
            </div>
        </div>

        
    </div>

    <div class="text-sm-left">
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <span>{!! \Session::get('success') !!}</span>
        </div>
        @endif
    </div>

    <div class="text-sm-left">
        @if (\Session::has('error'))
        <div class="alert alert-error">
            <span>{!! \Session::get('error') !!}</span>
        </div>
        @endif
    </div>
    <!-- end page title -->
    
    

    <div class="row">
        {{-- Auto payout --}}
        <form action="{{ route('pay-to-agent') }}" method="post"> 
            @csrf
            <input type="hidden" name="agent_payouts_ids" id="agent_payouts_ids" />
            <button class="btn btn-info pay-to-driver d-none" id="pay-to-driver" type="submit">Pay</button>
        </form>
        <input type="hidden" id="routes-listing-status" value="settlement">
        {{-- Table start --}}
        <div class="table-responsive">
            <table class="table table-striped driver-datatable">
                <thead class="thead-light ">
                    <tr>
                        <th>
                            <input type="checkbox" name="" id="checkAll" class="form-control">
                        </th>
                        <th>Order No.</th>
                        <th>Delivery Boy ID</th>
                        <th>Delivery Boy Name</th>
                        <th>Delivery Boy Phone</th>
                        <th>Vendor Name</th>
                        <th>Distance</th>
                        <th>Duration</th>
                        <th>Cash</th>
                        <th>Driver Cost</th>
                        <th>Employee Commission Percentage</th>
                        <th>Employee Commission Fixed</th>
                        <th>Order Amount</th>
                        <th>Pay To Driver</th>
                        <th>Payment Type</th>
                        <th>Tip</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
        {{-- Table End --}}
    </div>





</div> <!-- container -->
@endsection

@section('script')
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script>
$(document).ready(function(){

    // 
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    hash = hashes[0].split('=');
    if(hash[0] == 'status'){
        $('#routes-listing-status').val(hash[1]);
    }else{
        $('#routes-listing-status').val('unassigned');
    }

    // Date picker
    $('#date_picker').flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d H:i",
        mode : 'range',
        // minDate: "today",
        onClose: function(selectedDates, dateStr, instance) {
            initializeAgentListing();
        }
    });

    $("#checkAll").click(function(){
        $('.agent_payouts_id').not(this).prop('checked', this.checked);
        var checkBox        = $('input[name="agent_payouts_id[]"]:checked');
        if( checkBox.length > 0 ) {
            markChecked(checkBox);
        }
        else {
            $("#agent_payouts_ids").val('');
        }
    });
    $('.agent-class').select2({
        // placeholder: 'Keyword...',
        // ajax: {
        //     type: 'GET',
        //     url: "{{route('driver-list')}}",
        //     processResults: function(data) {
        //         return {
        //             results: $.map(data, function(item) {
        //                 return {
        //                     text: item.name,
        //                     id: item.id
        //                 }
        //             })
        //         };
        //     }
        // }
    });

    initializeAgentListing();
});

function initializeAgentListing(){
    console.log('here coming');
    $('.driver-datatable').DataTable({
        "dom": '<"toolbar">Bfrtip',
        "destroy": true,
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "iDisplayLength": 20,
        "paging": true,
        "lengthChange" : true,
        "searching": true,
        language: {
                    search: "",
                    paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                    searchPlaceholder: "{{__('Search Name, Mobile ')}}",
                    'loadingRecords': '&nbsp;',
                    //'sProcessing': '<div class="spinner" style="top: 90% !important;"></div>'
                    'sProcessing':function(){
                        spinnerJS.showSpinner();
                        spinnerJS.hideSpinner();
                    }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        },
        buttons: [{
            // className:'btn btn-success waves-effect waves-light',
            // text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>{{__("Export CSV")}}',
            // action: function ( e, dt, node, config ) {
            //     window.location.href = "{{ route('task.export') }}";
            // }
            
        }],
        ajax: {
            url: "{{route('driver-datatable')}}",
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: function (d) {
                d.search = $('input[type="search"]').val();
                d.routesListingType = $('#routes-listing-status').val();
                d.date_filter = $('#date_picker').val();
                d.imgproxyurl = '';
            }
        },
        columns: dataTableColumn(),
    });
}
function dataTableColumn(){
    return [
        {data: 'id', name: 'id', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
            return '<input type="checkbox" name="agent_payouts_id[]" class="form-control agent_payouts_id" value="'+full.agent_payouts_id+'">';
                                
        }},
        {data: 'order_number', name: 'order_number', orderable: false, searchable: false },
        {data: 'delivery_boy_id', name: 'delivery_boy_id', orderable: false, searchable: false},
        {data: 'delivery_boy_name', name: 'delivery_boy_name', orderable: false, searchable: false},
        {data: 'delivery_boy_number', name: 'delivery_boy_number', orderable: false, searchable: false},
        {data: 'vendor_name', name: 'vendor_name', orderable: false, searchable: false},
        {data: 'distance', name: 'distance', orderable: false, searchable: false},
        {data: 'duration', name: 'duration', orderable: false, searchable: false},
        {data: 'cash', name: 'cash', orderable: false, searchable: false},
        {data: 'driver_cost', name: 'driver_cost', orderable: false, searchable: false},
        {data: 'employee_commission_percentage', name: 'employee_commission_percentage', orderable: false, searchable: false},
        {data: 'employee_commission_fixed', name: 'employee_commission_fixed', orderable: false, searchable: false},
        {data: 'order_amount', name: 'order_amount', orderable: false, searchable: false},
        {data: 'pay_to_driver', name: 'pay_to_driver', orderable: false, searchable: false},
        {data: 'payment_type', name: 'payment_type', orderable: false, searchable: false},
        {data: 'tip', name: 'tip', orderable: false, searchable: false},
    ];
}

$(document).on('change', ".agent_payouts_id", function () {
    var checkBox        = $('input[name="agent_payouts_id[]"]:checked');
    showHidePayBtn();
    markChecked(checkBox);
});


function markChecked(checkBox) {
    var idsArray = [];

    showHidePayBtn();

    checkBox.each(function(){
        idsArray.push($(this).val());
    });
    
    $("#agent_payouts_ids").val(idsArray.join(","));
}

function showHidePayBtn() {
    var checkboxLen = $('input[name="agent_payouts_id[]"]:checked').length;
    if(checkboxLen) {
        $("#pay-to-driver").removeClass('d-none');
    }
    else {
        $("#pay-to-driver").addClass('d-none');
    }
}

function handleClick(myRadio) {
    $('#getTask').submit();
}
</script>
@endsection