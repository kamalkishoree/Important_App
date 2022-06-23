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
                    <h4 class="page-title">{{__("Batch Details")}}</h4>
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
                                        
                                        <th class="sort-icon">{{__("Sr.no")}} </th>
                                        <th class="sort-icon">{{__("Batch Number")}}</th>
                                        <th class="sort-icon">{{__("Agent Name")}}</th>
                                        <th class="sort-icon">{{__("Batch Time")}}</th>
                                        <th >{{__("Action")}}</th>
                                    </tr>
                                </thead>
                                <tbody style="height: 8%;overflow: auto !important;">
                                @foreach($batchs as $batch)
                                <tr>
                                    <td>{{$batch->id}}</td>
                                    <td>{{$batch->batch_no}}</td>
                                    <td>{{$batch->agent_name??'Null'}}</td>
                                    <td>{{$batch->created_at??'Null'}}</td>
                                    <td><button class="btn btn-primary btn-sm" onclick="alert('Are you Sure you want to assign batch')">Assign Batch</button>
                                        <button class="btn btn-primary btn-sm" onclick="viewOrders({{$batch->id}})">View Orders</button></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <div id="batch_details_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h4 class="modal-title">{{__('Batch Details')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body px-3 py-0 allin batchData">
                        
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->

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

    function viewOrders(id)
    {
        $.ajax({
            type: "post",
            dataType: "json",
            headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
            url: "{{route('batchDetails')}}",
            data: { "id": id},
            success: function(response) {
              
                    $("#batch_details_modal .batchData").html('');
                    $("#batch_details_modal .batchData").append(response.success);
                    $("#batch_details_modal").modal('show');                  
              
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                alert(error_messages);
            },
        });

    }


</script>
    
@endsection
