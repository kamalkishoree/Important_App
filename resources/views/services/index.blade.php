@extends('layouts.vertical', ['title' => 'Services'])

@section('css')
<style>
    .table th,.table td, .table td {
        display: table-cell !important;
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
    .custom_top_bar button.btn.btn-blue {
    color: #fff;
    height: 37px;
    margin-right: 10px;
    max-width: initial;
}
.dataTables_wrapper .inner-div {
    vertical-align: bottom !important;
    margin-right: 10px;
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
                <h4 class="page-title page-title1">{{ __("Services") }}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body custom_body_card">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-left"></div>
                            <div class="text-sm-left">
                                @if (\Session::has('error'))
                                    <div class="alert alert-danger">
                                        <span>{!! \Session::get('error') !!}</span>
                                    </div>
                                @endif
                            </div>
                            @if (\Session::has('success'))
                                <div class="alert alert-success @if(@$order_panel->sync_status && $order_panel->sync_status == 1) 'syncProcessing' @endif">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                            @elseif(@$order_panel->sync_status && $order_panel->sync_status == 1) <!--processing-->
                                <div class="alert alert-success syncProcessing">
                                    <span>{{__('Services & Product Import Is Processing.')}}</span>
                                </div>
                            @endif
                            @if(@$order_panel->sync_status && $order_panel->sync_status == 2) <!--processing-->
                                <div class="alert alert-success" id="syncCompleted">
                                    <span>{{__('Services & Product Import Is Completed.')}}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-sm-12 text-right btn-auto d-flex custom_top_bar">
                            <form method="get" id="db_form">
                                <div class="form-group">
                                    <select name="order_panel_id" id="db_name" class="form-control" style="width: 200px;margin-right: 10px;">
                                        <option value="" @if (app('request')->input('order_panel_id') == '') {{'selected="selected"'}} @endif>All</option>
                                        @foreach ($order_db_detail as $detail)
                                            <option value="{{$detail->id}}" @if (app('request')->input('order_panel_id') == $detail->id) {{'selected="selected"'}} @endif>{{$detail->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            {{-- <button type="button" class="btn btn-blue waves-effect waves-light openAddProductModal"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Product")}}</button>

                            <button type="button" class="btn btn-blue waves-effect waves-light openCategoryModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Add Category")}}</button> --}}
                            <form action="{{route('category.importOrderSideCategory')}}" method="post">
                            @csrf
                                <input type="hidden" name="order_panel_id" value="{{app('request')->input('order_panel_id') ?? 'all'}}">
                                <button type="submit" class="btn btn-blue waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> {{__("Import Order Side Category")}}</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive nagtive-margin">
                        <table class="table table-striped dt-responsive nowrap w-100" id="category-datatable">
                            <thead>
                                <tr>
                                    {{-- <th>{{__("#")}}</th> --}}
                                    <th>{{__("Name")}} <i class="fa fa-sort ml-1" aria-hidden="true"></i></th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__("Created Date")}}</th>
                                    <th>{{__("Total Products")}}</th>
                                    <th style="width: 85px;">{{__("Action")}}</th>
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

@endsection
@section('script')
    <script src="{{asset('assets/js/storeAgent.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    @include('services.services-script')  
@endsection