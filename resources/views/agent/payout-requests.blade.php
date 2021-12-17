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

    .nagtive-margin {
        margin-top: -57px;
    }
</style>
@endsection
@php
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp
@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right"></div>
                    <h4 class="page-title">{{ __("Payout Requests") }}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_earnings_by_vendors">{{$total_order_value}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Order Value') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_paid_payouts">{{$pending_payout_value}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0"> {{ __('Pending Payout Value') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_pending_payouts">{{$completed_payout_value}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Completed Payout Value') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pending-payouts" data-toggle="tab" href="#pending_payouts" role="tab" aria-selected="false" data-rel="pending_payouts_datatable" data-status="0">
                            <i class="icofont icofont-man-in-glasses"></i>{{ __('Pending') }}<sup class="total-items" id="pending_payouts_count">({{$pending_payout_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="completed-payouts" data-toggle="tab" href="#completed_payouts" role="tab" aria-selected="true" data-rel="completed_payouts_datatble" data-status="1">
                            <i class="icofont icofont-ui-home"></i>{{ __('Completed') }}<sup class="total-items">({{$completed_payout_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material pt-0" id="top-tabContent">
                    <div class="tab-pane fade past-order show active" id="pending_payouts" role="tabpanel" aria-labelledby="pending-payouts">
                        <div class="row">
                            <div class="col-12">
                               
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            {{-- <form name="saveOrder" id="saveOrder"> @csrf</form> --}}
                                           
                                            <table class="table table-centered table-nowrap table-striped" id="pending_payouts_datatable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Agent') }}</th>
                                                        {{-- <th>{{ _('Requested By') }}</th> --}}
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Payout Type') }}</th>
                                                        <th class="text-center">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="pending_payouts_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="completed_payouts" role="tabpanel" aria-labelledby="completed-payouts">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="completed_payouts_datatble" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Agent') }}</th>
                                                        {{-- <th>{{ _('Requested By') }}</th> --}}
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Payout Type') }}</th>
                                                        <th class="text-center">{{ __('Status') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="completed_payouts_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="payout-confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- <div class="modal-header border-0">
                <h4 class="modal-title">Payout</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div> --}}
            <form id="payout_form_final" method="POST" action="">
                @csrf
                <div>
                    <input type="hidden" name="amount" id="payout_amount" value="">
                </div>
                <div class="modal-body px-3">
                    <div class="row">
                        <h4 class="modal-title">{{__('Are you sure you want to payout')}} 
                            <span id="payout-vendor"></span> for
                            <span id="payout-amount-final"></span>?
                        </h4>
                    </div>
                </div>
                <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                    <button type="submit" class="btn btn-info waves-effect waves-light">{{__('Continue')}}</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- @include('agent.modals')
@include('modals.pay-receive') --}}
@endsection

@section('script')

<!-- <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}"> -->
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />>


<script>
    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){$('#pending-payouts').trigger('click');}, 200);
        $(document).on("click",".nav-link",function() {
            let rel= $(this).data('rel');
            let status= $(this).data('status');
            initDataTable(rel, status);
        });

        // initDataTable();

        function initDataTable(table, status) {
            $('#'+table).DataTable({
                "destroy": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 20,
                "dom": '<"toolbar">Btrip',
                // language: {
                //     search: "",
                //     paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                //     searchPlaceholder: "Search By "+search_text+" Name"
                // },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [],
                ajax: {
                  url: "{{route('agent.payout.requests.filter')}}",
                  data: function (d) {
                    d.status = status;
                //     d.search = $('input[type="search"]').val();
                //     d.date_filter = $('#range-datepicker').val();
                //     d.payment_option = $('#payment_option_select_box option:selected').val();
                //     d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                  }
                },
                columns: [
                    {data: 'date', name: 'date', orderable: false, searchable: false},
                    {data: 'agentName', name: 'agentName', orderable: false, searchable: false},
                    // {data: 'requestedBy', name: 'requestedBy', orderable: false, searchable: false},
                    {data: 'amount', class:'text-center', name: 'amount', orderable: false, searchable: false},
                    {data: 'type', name: 'type', orderable: false, searchable: false},
                    {data: 'status', class:'text-center', name: 'status', orderable: false, searchable: false, "mRender":function(data, type, full){
                        if(full.status == 'Pending'){
                            return "<button class='btn btn-sm btn-info payout_btn' data-id='"+full.id+"'>Payout</button>";
                        }else{
                            return full.status;
                        }
                    }},
                ]
            });
        }

        $(document).delegate(".payout_btn", "click", function(){
            var vendor = $(this).closest('tr').find('td:nth-child(2)').text();
            var amount = $(this).closest('tr').find('td:nth-child(4)').text();
            var dataid = $(this).attr('data-id');
            $("#payout_form_final").attr('action', "{{url('client/account/vendor/payout/request/complete')}}"+'/'+dataid);
            $("#payout-confirm-modal #payout-vendor").text(vendor);
            $("#payout-confirm-modal #payout-amount-final").text('{{$currency_symbol}}' + amount);
            $("#payout-confirm-modal #payout_amount").val(amount);
            $("#payout-confirm-modal").modal('show');
        });
    });

</script>
{{-- @include('agent.pagescript') --}}
@endsection