@extends('layouts.vertical', ['title' => __('Route')])

@section('css')
@endsection
@php
use Carbon\Carbon;
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
    .agents-datatable tbody td,.dataTables_scrollHead thead th {
        display: table-cell !important;
    } 
    #wrapper {
        overflow: auto !important;
    }
    .footer{
        z-index: 3;
    }
    #agents-datatable_processing {
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
    div#agents-datatable_filter {
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
    .dataTables_scrollHead thead th {
        cursor: pointer;
    }
    .btn-label,.btn-label:focus,.btn-label:active {
        background-color: rgb(102 88 221) !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{__("Routes")}}</h4>
                </div>
            </div>
        </div>
    
        
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-sm-left">
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @csrf
                          
                        </div>
                        <input type="hidden" id="routes-listing-status" value="unassigned">
                        <div class="table-responsive mn-4">
                            <table class="table table-striped dt-responsive nowrap w-100 agents-datatable" id="agents-datatable">
                                <thead>
                                    <tr>
                                        @if (!isset($status) || $status == 'unassigned')
                                        <th><input type="checkbox" class="all-driver_check" name="all_driver_id" id="all-driver_check"></th>
                                        @endif
                                        <th class="sort-icon">{{__("Batch Number")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                        <th style="width: 85px;">{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody style="height: 8%;overflow: auto !important;">
                                
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>


    </div>

    {{-- @include('modals.task-list')
    @include('modals.task-accounting')
    @include('modals.task-proofs')
    @include('modals.assgin_task_agent')
    @include('modals.assgin_task_date') --}}
@endsection

@section('script')
    <script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    {{-- @include('tasks.taskpagescript') --}}

<script>
     initializeRouteListing();
        function initializeRouteListing(){

            $('.agents-datatable').DataTable({
                "dom": '<"toolbar">Bfrtip',
                "destroy": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "iDisplayLength": 10,
                "paging": true,
                "lengthChange" : true,
                "searching": true,
                // "ordering": true,
                language: {
                            search: "",
                            paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                            searchPlaceholder: "{{__('Search Routes')}}",
                            'loadingRecords': '&nbsp;',
                            'sProcessing': '<div class="spinner" style="top: 90% !important;"></div>'
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [{
                    className:'btn btn-success waves-effect waves-light',
                    text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>{{__("Export CSV")}}',
                    action: function ( e, dt, node, config ) {
                        window.location.href = "{{ route('task.export') }}";
                    }
                }],
                ajax: {
                    url: "{{url('task/filter')}}",
                    // "dataSrc": "",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                        d.routesListingType = $('#routes-listing-status').val();
                        d.imgproxyurl = '{{$imgproxyurl}}';
                    }
                },
               // order: dataTableColumnSort(),
                columns: dataTableColumn(),
            });
        }

        function dataTableColumnSort(){
            var routesListing = $('#routes-listing-status').val();
            if(routesListing == 'unassigned'){
                return [[ 10, "desc" ]];
            }else{
                return [[ 10, "desc" ]];
            }
        }

        function dataTableColumn(){
            var routesListing = $('#routes-listing-status').val();
            if(routesListing == 'unassigned'){
                return [
                    {data: 'id', name: 'id', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<input type="checkbox" class="single_driver_check" name="driver_id[]" id="single_driver" value="'+full.id+'">';
                    }},
                    // {data: 'order_number', name: 'order_number', orderable: true, searchable: false},
                    {data: 'customer_id', name: 'customer_id', orderable: true, searchable: false},
                    {data: 'order_number', name: 'order_number', orderable: true, searchable: false , "mRender": function ( data, type, full ) {
                        if(full.request_type=='D')
                        return full.order_number+' (Delivery)';
                        
                        return full.order_number+' (Pickup)';

                    }},
                    {data: 'customer_name', name: 'customer_name', orderable: true, searchable: false},
                    {data: 'phone_number', name: 'phone_number', orderable: true, searchable: false},
                    {data: 'agent_name', name: 'agent_name', orderable: true, searchable: false},
                    {data: 'order_time', name: 'order_time', orderable: true, searchable: false},
                    {data: 'short_name', name: 'short_name', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        var shortName = JSON.parse(full.short_name.replace(/&quot;/g,'"'));
                        var routes = '';
                        $.each(shortName, function(index, elem) {
                            routes += '<div class="address_box"><span class="'+elem.pickupClass+'">'+elem.taskType+'</span> <span class="short_name">'+elem.shortName+'</span> <label class="datatable-cust-routes" data-toggle="tooltip" data-placement="bottom" title="'+elem.toolTipAddress+'">'+elem.address+'</label></div>';
                        });
                        return routes;
                    }},
                    {data: 'track_url', name: 'track_url', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        var trackUrl = full.track_url;
                        return '<a onclick="window.open(this.href,"_blank");return false;" href="'+trackUrl+'">'+'{{__("Track")}}'+'</a>';
                    }},
                    {data: 'track_url', name: 'track_url', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<button class="showTaskProofs btn btn-primary-outline action-icon" value="'+full.id+'"><i class="fe-layers"></i></button>';
                    }},
                    {data: 'order_cost', name: 'order_cost', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<button class="showaccounting btn btn-primary-outline action-icon setcolor" value="'+full.id+'">'+full.order_cost+'</button>';
                    }},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
                    {data: 'action', name: 'action', orderable: true, searchable: false}
                ];
            }else{
                return [
                    // {data: 'order_number', name: 'order_number', orderable: true, searchable: false},
                    {data: 'customer_id', name: 'customer_id', orderable: true, searchable: false},
                    {data: 'order_number', name: 'order_number', orderable: true, searchable: false},
                    {data: 'customer_name', name: 'customer_name', orderable: true, searchable: false},
                    {data: 'phone_number', name: 'phone_number', orderable: true, searchable: false},
                    {data: 'agent_name', name: 'agent_name', orderable: true, searchable: false},
                    {data: 'order_time', name: 'order_time', orderable: true, searchable: false},
                    {data: 'short_name', name: 'short_name', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        var shortName = JSON.parse(full.short_name.replace(/&quot;/g,'"'));
                        var routes = '';
                        $.each(shortName, function(index, elem) {
                            routes += '<div class="address_box"><span class="'+elem.pickupClass+'">'+elem.taskType+'</span> <span class="short_name">'+elem.shortName+'</span> <label data-toggle="tooltip" data-placement="bottom" title="'+elem.toolTipAddress+'">'+elem.address+'</label></div>';
                        });
                        return routes;
                    }},
                    {data: 'track_url', name: 'track_url', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        var trackUrl = full.track_url;
                        return '<a onclick="window.open(this.href,"_blank");return false;" href="'+trackUrl+'">Track</a>';
                    }},
                    {data: 'track_url', name: 'track_url', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<button class="showTaskProofs btn btn-primary-outline action-icon" value="'+full.id+'"><i class="fe-layers"></i></button>';
                    }},
                    {data: 'order_cost', name: 'order_cost', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return '<button class="showaccounting btn btn-primary-outline action-icon setcolor" value="'+full.id+'">'+full.order_cost+'</button>';
                    }},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
                    {data: 'action', name: 'action', orderable: true, searchable: false}
                ]
            }
        }


    });
</script>
    
@endsection
