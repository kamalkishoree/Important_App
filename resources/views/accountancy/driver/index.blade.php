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
.agents-datatable thead{
    width: 0;
}
.agents-datatable tbody td, .dataTables_scrollHead thead th {
    padding: 0 !important;
}
.table-responsive input#checkAll {
    width: 24px;
}
table.dataTable tbody tr td input {
    width: 24px;
}

div#DataTables_Table_0_filter label input {
    margin-bottom: 30px;
}
.col-md-12.main_form .col-md-2.mt-3 button:hover {
    background-color: #6658dd;
    border-color: #6658dd;
    cursor: pointer;
}
.table th, .table td {
    vertical-align: middle !important;
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
                <div class="col-md-5">
                    <form action="{{ route('driver-accountancy.index') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">From Date</label>
                                <input type="text" name="date_picker" id="date_picker" class="form-control" placeholder="Select Date Range">
                            </div>
                            <div class="col-md-6">
                                <label for="">Select Driver</label>
                                {{Form::select('agent_id', ['' => 'Please Select '.__(getAgentNomenclature())] + $agentList, '', array('class' => 'form-control', 'id' => 'agent_id' ))}}
                                
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-3 d-flex align-items-center mt-3">
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
                <div class="col-md-2 d-flex align-items-center mt-3">
                    <div class="text-center outter_bx">
                        <p class="h4">Total Commision : <span class="total_commission"></span></p>                        
                    </div>
                </div>
                <div class="col-md-2  mt-3">
                    <form action="{{ route('pay-to-agent') }}" method="post"> 
                        @csrf
                        <input type="hidden" name="agent_payouts_ids" id="agent_payouts_ids" />
                        <button class="btn btn-info btn-block pay-to-driver d-none" id="pay-to-driver" type="submit">Pay</button>
                    </form>
                </div>
            </div>
            

            
            {{-- Filter form end --}}

            
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
        
        <input type="hidden" id="routes-listing-status" value="settlement">
        {{-- Table start --}}
        <div class="table-responsive mt-4">
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
                        <th>{{__(getAgentNomenclature())}} Name</th>
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

    $('.agent-class').on('select2:select', function (e) {
        initializeAgentListing();
    });

    // get query parameter
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    hash = hashes[0].split('=');
    console.log(hash[1]);
    
    if( hash[1] == undefined ) {
        var status = "{{ $status }}";
        $('#routes-listing-status').val(status);
    } else if(hash[0] == 'status'){
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

    $("#checkAll").change(function(){
        showHidePayBtn();
        $('.agent_payouts_id').not(this).prop('checked', this.checked);
        var checkBox        = $('input[name="agent_payouts_id[]"]:checked');
        if( checkBox.length > 0 ) {
            markChecked(checkBox);
        }
        else {
            $("#agent_payouts_ids").val('');
            $(".pay-to-driver").addClass('d-none');
        }
    });

    $("#agent_id").change(function(){
        initializeAgentListing();
    });
    $('.agent-class').select2({
        placeholder: 'Keyword...',
        ajax: {
            type: 'GET',
            url: "{{route('driver-list')}}",
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });

    initializeAgentListing();
});

function initializeAgentListing(){
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
                d.driver_id = $("#agent_id").val();
                d.imgproxyurl = '';
            }
        },
        columns: dataTableColumn(),
    });
}
function dataTableColumn(){
    return [
        {data: 'id', name: 'id', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
            var total_commission = $('.total_commission').text();
            if( full.agent_sum != total_commission ) {
                $('.total_commission').text(full.agent_sum);
            }
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
    // console.log('checkboxLen');
    // console.log(checkboxLen);
    if(checkboxLen > 0) {
        $(".pay-to-driver").removeClass('d-none');
    }
    else {
        $(".pay-to-driver").addClass('d-none');
    }
}

function handleClick(myRadio) {
    $('#getTask').submit();
}
</script>
@endsection